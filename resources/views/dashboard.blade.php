<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- {{ __("You're logged in!") }} --}}
                    <x-link href="{{ route('subDomain.create') }}" class="mb-4">{{ __('Add Sub Domain') }}</x-link>
                  {{-- {{Auth::user()}} --}}
                  {{-- <h1>{{Auth::user()->id}}</h1> --}}

                    @php
                   
                        $subDomainUser =DB::table('multi_tenant.domains as subdomain')
                                       ->join('multi_tenant.tenant_user as tenant','tenant.tenant_id','subdomain.tenant_id')
                                       ->select('subdomain.domain','subdomain.tenant_id','subdomain.id','subdomain.status','subdomain.is_redirect_url')
                                       ->where('tenant.user_id',Auth::user()->id)->get();
                                     
                    @endphp
                   
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                Domain Name
                            </th>
                            {{-- <th scope="col" class="px-6 py-3 text-left">
                                Central Domain Name
                            </th> --}}
                            <th scope="col" class="px-6 py-3 text-left">
                                Primary 
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                Redirect Domain 
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Actions
                            </th>

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($subDomainUser as $subdomain)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $subdomain->domain .'.'.config('tenancy.central_domains')[0] }}
                                </td>
                                {{-- <td>
                                    {{config('tenancy.central_domains')[0]}}
                                </td> --}}
                                <td>
                                    @if ($subdomain->status ==1)
                                    {{-- {{ __('PRIMARY') }} --}}
                                    {{-- <x-text-input type="checkbox" name="primery_is" onclick="primeryURL(1,$subdomain->id)" checked /> --}}
                                        <x-button style="background-color: #2cd541;">
                                            {{ __('PRIMARY') }}
                                        </x-button>
                                     @else
                                    {{-- <input type="checkbox" name="primery_is" id="" onclick="primeryURL(this)" value="{{$subdomain->id}}"> --}}
                                    <x-button onclick="primeryURL(this)" value="{{$subdomain->id}}" style="background-color: #0943c5;">
                                        {{ __('Make Primary') }}
                                    </x-button>
                                     
                                    @endif
                                    
                                </td>
                                <td>
                                    @if ($subdomain->status ==1)

                                    @else
                                        @if ($subdomain->is_redirect_url ==1)
                                    
                                        {{-- <x-text-input type="checkbox" name="redirect_is" onclick="redirectURL(1,$subdomain->id)"  checked  /> --}}
                                            {{-- <input type="checkbox" name="primery_is" id="" onclick="redirectURL(this)" value="{{$subdomain->id}}" checked> --}}
                                            <x-button onclick="redirectURL(this)" value="{{$subdomain->id}}" style="background-color: #2cd541;">
                                                {{ __('Do Not Redirect') }}
                                            </x-button>
                                        
                                        @else
                                    
                                        {{-- <input type="checkbox" name="primery_is" id="" onclick="redirectURL(this)" value="{{$subdomain->id}}"> --}}
                                        <x-button onclick="redirectURL(this)" value="{{$subdomain->id}}" style="background-color: #0943c5;">
                                            {{ __('Redirect Primary') }}
                                        </x-button>
                                        @endif

                                    @endif
                                   
                                    
                                </td>
                                <td class="px-6 py-4">
                                    <x-link href="{{'https://' . $subdomain->domain . '.'. config('tenancy.central_domains')[0] .'/dashboard1'}}" target="_blank">View</x-link>
                                    <x-link href="#">Edit</x-link>
                                    {{-- <form method="POST" action="{{ route('subDomain.destroy', $subdomain->id) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE') --}}
                                        <x-button class="bg-red-600" >Delete</x-button>
                                        
                                    {{-- </form> --}}
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="2"
                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ __('No projects found') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                   
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
<script>
    function primeryURL(x) {
      
        var subDomain_id = x.value; 
         
        $.ajax({
            type: "get",
            dataType: "json",
            url: '/changePrimeryStatus',
            data: {'sub_domain_id': subDomain_id},
            success: function(data){
             
              window.location.reload();
            }
        });
        
    };
    function redirectURL(y) {
      
        var subDomain_id = y.value; 
         
        $.ajax({
            type: "get",
            dataType: "json",
            url: '/changeRedirectStatus',
            data: {'sub_domain_id': subDomain_id},
            success: function(data){
              //console.log(data.success)
              window.location.reload();
            }
        });
    }

</script>
