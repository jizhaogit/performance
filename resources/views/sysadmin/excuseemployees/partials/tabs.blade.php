<div class="d-flex justify-content-center justify-content-lg-start mb-2" role="tablist">
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.excuseemployees.addindex' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.excuseemployees.addindex')" style="">
          Excuse New Employee(s)
        </x-button>
    </div>
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.excuseemployees.manageindex' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.excuseemployees.manageindex')" style="">
          Manage Existing Excused
        </x-button>
    </div>
</div>
