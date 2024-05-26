<?php

namespace App\Http\Controllers;

use App\Vehicle;
use App\User;
use App\Role;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class VehicleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:vehicles_access'])->only(['create','store']);
        $this->middleware(['permission:vehicles_show'])->only('show');
        $this->middleware(['permission:vehicles_edit'])->only(['edit','update']);
        $this->middleware(['permission:vehicles_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if($user->isSuperAdmin()){
            $vehicles = Vehicle::orderBy('id', 'ASC')
            ->paginate(20);
        }else{
            $vehicles = $user->vehicles()
                        ->orderBy('id', 'ASC')
                        ->paginate(20);
        }

        return view('app.vehicles.list', ['user' => $user, 'vehicles' => $vehicles, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::find(2);

        $drivers = User::role($role->name)->get();
        return view('app.vehicles.create', ['drivers' => $drivers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'make' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
            'model' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
            'year' => ['required', 'integer',],
            'licensePlate' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
            'driver_id' => ['required', 'integer', 'not_in:0'],
        ]);

        $validator->validate();

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vehicle = Vehicle::create([
            'user_id' => $data['driver_id'],
            'make' => $data['make'],
            'model' => $data['model'],
            'year' => $data['year'],
            'license_plate' => $data['licensePlate']
        ]);

        if($vehicle) {
            return redirect('vehicles')->with('success', __("vehicle created!"));
        }else{
            return redirect('vehicles/create')->with('error',__("There has been an error!"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $vehicle = Vehicle::find($id);
        return view('app.vehicles.show', ['user' => $user, 'vehicle' => $vehicle]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $role = Role::find(2);
        $drivers = User::role($role->name)->get();
        $vehicle = Vehicle::find($id);
        
        return view('app.vehicles.edit', ['user' => $user, 'vehicle' => $vehicle, 'drivers' => $drivers]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);

        if($vehicle){

            $user = Auth::user();

            if(!$user->isSuperAdmin()){
                return redirect('vehicles')->with('error',__("Super admin user cannot be deleted!"));
            }

            $data = $request->all();

            $validator = Validator::make($data, [
                'make' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
                'model' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
                'year' => ['required', 'integer',],
                'licensePlate' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
                'driver_id' => ['required', 'integer', 'not_in:0'],
            ]);
    
            $validator->validate();
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if($vehicle->update($data)){
                return redirect('vehicles/'.$id)->with('success',__("vehicle updated!"));
            } else {
                return redirect('vehicles/' . $id)->with('error', __("Failed to update vehicle!"));
            }

        } else {
            return redirect('vehicles')->with('error',__("vehicle not found!"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::find($id);

        if($vehicle){

            $user = Auth::user();

            if(!$user->isSuperAdmin()){
                return redirect('vehicles')->with('error',__("Super admin user cannot be deleted!"));
            }

            $vehicle->delete();
            return redirect('vehicles')->with('success',__("Vehicle deleted!"));

        } else {
            return redirect('vehicles')->with('error',__("Vehicle not found!"));
        }
    }

    public function updateStatus ($id)
    {
        $user = Auth::user();

        if(!$user->isSuperAdmin()){
            return redirect('vehicles')->with('error',__("Super admin user cannot be deleted!"));
        }

        $vehicle = Vehicle::find($id);

        if($vehicle){
            $vehicle->status = !$vehicle->isStatus();

            if($vehicle->save()){
                return redirect('vehicles/'.$id)->with('success',__("vehicle updated!"));
            }else {
                return redirect('vehicles/' . $id)->with('error', __("Failed to update vehicle status!"));
            }
        } else {
            return redirect('vehicles')->with('error',__("vehicle not found!"));
        }

        return view('app.vehicles.document', ['user' => $user, 'vehicle' => $vehicle]);
    }

    public function showDocument(Request $request, $id)
    {
        $user = Auth::user();

        $vehicle = Vehicle::find($id);

        $images =  $this->showFileVehicle($id, "gallery");
        
        return view('app.vehicles.document', ['user' => $user, 'vehicle' => $vehicle,'images' => $images]);
    }

    public function showPayment(Request $request, $id)
    {
        $user = Auth::user();

        $vehicle = Vehicle::find($id);

        $payments = $vehicle->payments;
        
        return view(
            'app.vehicles.payments', 
            [
                'user' => $user,
                'vehicle' => $vehicle,
                'payments' => $payments
            ]
        );
    }

    public function createPayment(Request $request, $id){
        $vehicle = Vehicle::find($id);
        return view('app.vehicles.payment_create', [
            'vehicle' => $vehicle
        ]);
    }

    public function viewPayment(Request $request, $id, $id_payment){
        $user = Auth::user();
        $vehicle = Vehicle::find($id);
        $payment = Payment::find($id_payment);
        return view('app.vehicles.payment_show', [
            'user' => $user,
            'vehicle' => $vehicle,
            'payment' => $payment
        ]);
    }

    public function storePayment(Request $request, $id) {
        try {

            $data = $request->all();
            $vehicle = Vehicle::find($id);
        
            if ($vehicle) {
                $validator = Validator::make($data, [
                    'amount' => 'required|numeric',
                    'payment_method' => 'required|string',
                    'payment_date' => 'required|date'
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
        
                $payment = Payment::create([
                    'vehicle_id' => $id,
                    'amount' => $data['amount'],
                    'payment_method' => $data['payment_method'],
                    'payment_date' => $data['payment_date'],
                ]);
        
                return redirect('vehicles/'.$id.'/payments')->with('success', __("Payment created!"));
            } else {
                return redirect('vehicles/'.$id.'/create_payments')->with('error', __("There has been an error!"));
            }

        }catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the photo.', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateApproved ($id){

        $user = Auth::user();

        if(!$user->isSuperAdmin()){
            return redirect('vehicles')->with('error',__("Super admin user cannot be deleted!"));
        }

        $payment = Payment::find($id);

        if ($payment) {

            $payment->status = "Approved";

            if($payment->save()){
                return redirect('vehicles/'.$payment->vehicle_id.'payments'.$payment->id)->with('success',__("Payment updated!"));
            }else {
                return redirect('vehicles/'.$payment->vehicle_id.'payments'.$payment->id)->with('error', __("Failed to update Payment status!"));
            }

        }else{
            return response()->json(['error' => __("Payment not found!")]);
        }

    }

    private function showFileVehicle($id, $search){
        $images = File::files(storage_path('app/vehicles/'));

        $imageFiles = array_filter($images, function ($file) use ($search, $id) {

            $mimeType = File::mimeType($file);
            $fileName = basename($file);

            $parts = explode('_', $fileName);

            if (count($parts) >= 3) {
                $fileIdPart = basename($parts[count($parts) - 1], '.' . File::extension($fileName));
                $matchesSearchAndId = ($parts[0] === $search) && ($fileIdPart === (string)$id);

                return str_contains($mimeType, 'image/') && $matchesSearchAndId;
            }

            return false;
        });

        return $imageFiles;

    }


}
