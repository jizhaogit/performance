<div class="d-flex justify-content-center justify-content-lg-start mb-2" role="tablist">
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.sharedemployees.notify' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.sharedemployees.notify')" style="">
          Share new Employee(s)
        </x-button>
    </div>
    {{-- <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.sharedemployees' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.sharedemployees')" style="">
          Managed Shared Employees
        </x-button>
    </div> --}}
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.shared.manageexistingshares' ? 'border-primary' : ''}}">
      <x-button :href="route('sysadmin.shared.manageexistingshares')" style="">
          Manage Existing Shares
      </x-button>
  </div>
</div>