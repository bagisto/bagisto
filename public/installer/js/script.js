window.onload = function() {

    var welcome = document.getElementById('welcome');
    var requirement = document.getElementById('requirement');
    var environment = document.getElementById('environment');
    var migration = document.getElementById('migration');
    var admin =  document.getElementById('admin');

    var welcomeCheck = document.getElementById('welcome-check');
    var requirementCheck = document.getElementById('requirement-check');
    var requirementsRefresh = document.getElementById('requirements-refresh');

    var permisssionCheck = document.getElementById('permission-check');
    var environmentCheck = document.getElementById('environment-check');
    var continue_to_admin =  document.getElementById('continue');

    var permissionBack = document.getElementById('permission-back');
    var requirementBack = document.getElementById('requirement-back');
    var envBack = document.getElementById('envronment-back');
    var migrationBack = document.getElementById('migration-back');

    if (requirementCheck) {
        requirementCheck.addEventListener('click', myFunction);
    }

    if (requirementsRefresh) {
        requirementsRefresh.addEventListener('click', myFunction);
    }

    if (welcomeCheck) {
        welcomeCheck.addEventListener('click', myFunction);
    }

    if (permisssionCheck) {
        permisssionCheck.addEventListener('click', myFunction);
    }

    if (environmentCheck) {
        environmentCheck.addEventListener('click', myFunction);
    }

    if (continue_to_admin) {
        continue_to_admin.addEventListener('click', myFunction);
    }
    
    if (envBack) {
        envBack.addEventListener('click', myFunction);
    }

    if (requirementBack) {
        requirementBack.addEventListener('click', myFunction);
    }

    if (permissionBack) {
        permissionBack.addEventListener('click', myFunction);
    }

    if (migrationBack) {
        migrationBack.addEventListener('click', myFunction);
    }

    function myFunction() {
        if(this.id == 'welcome-check') {
            requirement.style.display = "block";
            welcome.style.display = "none";
        } else if (this.id == 'requirement-check') {
            environment.style.display = "block";
            requirement.style.display = "none";
        } else if (this.id == 'continue') {
            migration.style.display = "none";
            admin.style.display ="block";
        } else if (this.id == 'requirement-back') {
            welcome.style.display = "block";
            requirement.style.display = "none";
        } else if (this.id == 'envronment-back') {
            environment.style.display ="none";
            requirement.style.display = "block";
        } else if (this.id == 'migration-back') {
            migration.style.display = "none";
            environment.style.display ="block";
        } else if (this.id == 'requirements-refresh') {
            location.reload();
        }
    }
};
