<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Elmas\Tools\AuditMessages;

class ActivityLogController extends Controller
{

	public function __construct()
    {
        $this->middleware(['permission:activitylog_show'])->only('show');
        $this->middleware(['permission:activitylog_delete'])->only('destroy');
    }

    /**
     * Display a listing of the audits.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $audits = Audit::where('new_values','NOT LIKE','%remember_token%')->whereNotNull('user_id')->orderBy('created_at','DESC')->paginate(20);
        $audits = AuditMessages::get($audits);

        return view('app.activitylog.list', ['audits' => $audits]);
    }

    /**
     * Display the specified audit details.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $audit = Audit::find($id);

        if($audit){
        	$audit['event_message'] = AuditMessages::getMessage($audit);
        	
            return view('app.activitylog.show', ['audit' => $audit]);
        } else {
            return redirect('activitylog')->with('error',__("Activity not found!"));
        }
    }

    /**
     * Remove the specified audit from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $audit = Audit::find($id);

        if($audit){
            $audit->delete();
            return redirect('activitylog')->with('success',__("Activity deleted!"));
        } else {
            return redirect('activitylog')->with('error',__("Activity not found!"));
        }
    }
}
