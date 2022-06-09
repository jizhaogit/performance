<div class="d-flex justify-content-center justify-content-lg-start mb-2" role="tablist">
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.excusedemployees.notify' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.excusedemployees.notify')" style="">
          Excuse New Employee(s)
        </x-button>
    </div>
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.excused.manageexistingexcused' ? 'border-primary' : ''}}">
      <x-button :href="route('sysadmin.excused.manageexistingexcused')" style="">
          Manage Existing Excused
      </x-button>
  </div>
{{-- <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.excusedemployees' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.excusedemployees')" style="">
          Managed Excused Employees
        </x-button>
    </div> --}}
    {{-- <div class="px-4 py-1 mr-2 border-bottom {{ str_contains( Route::current()->getName(), 'generic-template' ) ? 'border-primary' : ''}}">
      <x-button role="tab" :href="route('generic-template.index')" style="">
        Generic Template
      </x-button>
    </div> --}}
</div>
