<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Measurement;
use Illuminate\Support\Facades\Hash;
use DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $employees = User::where('role', 'employee');
            
            return Datatables::of($employees)
            ->addColumn('actions', function ($employees) {
                return view('employees.actions', compact('employees'));
            })        
                    ->make(true);
        }
        return view('employees.index');
    }

    public function create()
    {
        return view('customers.create');
    }


    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:3|max:10',
            'measurement.*.height' => 'required',
            'measurement.*.weight' => 'required',
        ], [
            'measurement.*.height.required' => 'Height is required',
            'measurement.*.weight.required' => 'Weight is required',
        ]);

        
        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => 'customer'
            ]);
            
            $measurements = array_values($request->get('measurement'));

            foreach ($measurements as $lvl => $measurement) {
                Measurement::create([
                    'height' => $measurement['height'],
                    'user_id' => $user->id,
                    'weight' => $measurement['weight'],
                ]);
            }
            return ['response' => 1, 'msg' => 'User added successfully', 'redirect' => route('customers.index')];
        } catch (\Throwable $th) {
            if (config('app.env') === 'local') {
                $msg = $th->getMessage();
            } else {
                $msg = 'Failed to create user.';
            }

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('customers.create')];
        }
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|min:3|max:10',
        ]);

        try {
            $customer = User::findOrFail($id);
            $password = Hash::make($request->get('password'));
            $customer->password = $password;
            $customer->save();
            return ['response' => 1, 'msg' => 'Password Updated successfully', 'redirect' => route('customers.index')];
        } catch (\Throwable $th) {
            if (config('app.env') === 'local') {
                $msg = $th->getMessage();
            } else {
                $msg = 'Failed to update password.';
            }
            return ['response' => 2, 'msg' => $msg, 'redirect' => route('customers.edit',$customer->id)];
        }
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        try {
            if ($customer->id != auth()->user()->id) {
                $customer->delete();
                return [
                    'response' => 1,
                    'msg' => 'Customer deleted successfully.',
                    'redirect' => route('customers.index')
                ];
            } else {
                throw new \Exception('You Can\'t delete Your Self');
            }
        } catch (\Exception $e) {
            if (config('app.env') === 'local') {
                $msg = $e->getMessage();
            } else {
                $msg = 'Failed to delete user.';
            }

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('customers.index')];
        }
    }

}
