<div class="d-flex justify-content-center justify-content-lg-start mb-2" role="tablist">
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.goalbank.createindex' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.goalbank.createindex')" style="">
          Add a New Goal in Goal Bank
        </x-button>
    </div>
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.goalbank.manageindex' ? 'border-primary' : ''}}">
        <x-button role="tab" :href="route('sysadmin.goalbank.manageindex')" style="">
          Manage Goals in Goal Bank
        </x-button>
    </div>
</div>
