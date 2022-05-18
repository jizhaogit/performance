<div class="d-flex justify-content-center justify-content-lg-start mb-2">
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.employees.currentemployees' ? 'border-primary' : ''}}">
        <x-button :href="route('sysadmin.employees.currentemployees')" style="">
            Current Employees
        </x-button>
    </div>
    <div class="px-4 py-1 mr-2 border-bottom {{Route::current()->getName() == 'sysadmin.employees.pastemployees' ? 'border-primary' : ''}}">
        <x-button :href="route('sysadmin.employees.pastemployees')" style="">
            Past Employees
        </x-button>
    </div>
</div>

