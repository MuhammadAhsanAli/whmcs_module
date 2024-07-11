<h2>Manage Your Services</h2>

<button type="button" class="btn btn-primary" onclick="toggleCreateForm()">Create New Service</button>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Service ID</th>
        <th>Storage Space</th>
        <th>Bandwidth</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    {foreach $services as $service}
        <tr>
            <td>{$service.serviceid}</td>
            <td>{$service.storage}</td>
            <td>{$service.bandwidth}</td>
            <td>
                <form method="post" action="clientarea.php?custommodule_action=suspend">
                    <input type="hidden" name="serviceid" value="{$service.serviceid}">
                    <button type="submit" class="btn btn-warning">Suspend</button>
                </form>
                <form method="post" action="clientarea.php?custommodule_action=terminate">
                    <input type="hidden" name="serviceid" value="{$service.serviceid}">
                    <button type="submit" class="btn btn-danger">Terminate</button>
                </form>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>

<div id="createForm">
    {include file="modules/servers/customModule/templates/createForm.tpl"}
</div>

<script>
    function toggleCreateForm() {
        var createForm = document.getElementById('createForm');
        if (createForm.style.display === 'none' || createForm.style.display === '') {
            createForm.style.display = 'block';
        } else {
            createForm.style.display = 'none';
        }
    }
</script>
