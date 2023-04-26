<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Http\Requests\StoreSubDomainRequest;
use Auth;
use DB;

class subDomainController extends Controller
{
    public function index()
    {
       // $tasks = Task::with('project')->get();

        return view('dashboard');
    }

    public function create()
    {
       // $projects = Project::all();

        return view('sub_domain.create');
    }

    public function store(StoreSubDomainRequest $request)
    {
       // return $request;
        // Task::create($request->validated());
        $user =Auth::user();
        $tenant = Tenant::create([
            'name' => $request->sub_domain,
            
        ]);
        $tenant->domains()->create([
            'domain' => $request->sub_domain,
        ]);
        $user->tenants()->attach($tenant->id);

        return redirect()->route('subDomain.index');
    }

    public function edit(Task $task)
    {
       // $projects = Project::all();

        return view('tasks.edit');
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('subDomain.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('subDomain.index');
    }

    public function changePrimeryStatus(Request $request)
    {
      // return $request;
      $primerySubDomain =DB::table('multi_tenant.domains')->where('id', '=', $request->sub_domain_id)->first();
      $user_tenant = DB::table('multi_tenant.tenant_user')->where('user_id', '=', Auth::user()->id)->get();
     
      if($primerySubDomain){
        $primerySubDomain->status = $request->status;
        $subDomainUpdate = DB::table('multi_tenant.domains')->where('id', '=', $request->sub_domain_id)->update(['status' => 1,'is_redirect_url' => 0]);
       // $subDomainUpdate = DB::table('multi_tenant.domains')->where('id', '!=', $request->sub_domain_id)->update(['status' => 1]);
       foreach ($user_tenant as $key => $userP) {
            if ($userP->tenant_id == $primerySubDomain->tenant_id) {
                
            }else {
                $subDomainUpdate = DB::table('multi_tenant.domains')->where('tenant_id', '=', $userP->tenant_id)->update(['status' => 0]);
            }
       }

        //return redirect()->route('subDomain.index');
        return response()->json([
            'status'=> 200,
           
            'data'=>"done"
          ]);
      }
    }

    public function changeRedirectStatus(Request $request)
    {
      // return $request;
      $primerySubDomain =DB::table('multi_tenant.domains')->where('id', '=', $request->sub_domain_id)->first();
      $user_tenant = DB::table('multi_tenant.tenant_user')->where('user_id', '=', Auth::user()->id)->get();

      if ($primerySubDomain->is_redirect_url == 1) {
        if($primerySubDomain){
          $primerySubDomain->status = $request->status;
          $subDomainUpdate = DB::table('multi_tenant.domains')->where('id', '=', $request->sub_domain_id)->update(['is_redirect_url' => 0]);
         
        }
      } else {
        if($primerySubDomain){
          $primerySubDomain->status = $request->status;
          $subDomainUpdate = DB::table('multi_tenant.domains')->where('id', '=', $request->sub_domain_id)->update(['is_redirect_url' => 1]);
        
        }
      }
      
     
      
      return response()->json([
        'status'=> 200,
       
        'data'=>"done"
      ]);
    }
}
