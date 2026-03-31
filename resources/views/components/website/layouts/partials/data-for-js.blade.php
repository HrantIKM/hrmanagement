<script>
    // JS get route by route name
    const routesData = @json(getAllRoutesName());

    // Dates Format
    window.$dashboardDates = @json(getDashboardDates());

    // JS Translation
    window.trans = @json(getTrans());

    // Roles
    window.$app = {
        roles: @json(getAuthUserRolesName()),
        permissions: []
    }
</script>
