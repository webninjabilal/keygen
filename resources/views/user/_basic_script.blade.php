<script>
    $(document).ready(function () {
        var oTable = $('#user_list').dataTable({
            "oLanguage": { "sSearch": "" } ,
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 25,
            bSort: false,
            ajax : "{{ route('user_records') }}"
        });
    });
    function saveUser() {
        var validate = customValidations('create_user');
        if(!validate){
            return false;
        }
        return reloadAjaxSubmit('create_user',"{{ route('user.store') }}",'name','Submit');
    }
    function deleteUser(userId) {
        if(confirm('Are you sure you would like to delete it?')) {
            $.ajax({
                method: 'DELETE',
                url: '{{ route('user.destroy', $user)}}/'+userId,
                data: 'user_id='+userId+'&_token='+csrf_token,
            }).done(function() {
                toastrShow('User deleted Successfully!','Success');
                location.reload();
            });
        }
    }
    function getUser(userId) {
        $.ajax({
            method: 'GET',
            url: '{{ url('user')}}/'+userId
        }).done(function(data) {
            $('#update_user .modal-body').html(data);
            $('#update_user').modal('show');
        });
    }
    function updateUser() {
        var validate = customValidations('update_user');
        if(!validate){
            return false;
        }
        var user_id = $('#update_user').find('input[name=user_id]').val();
        return reloadAjaxSubmit('update_user',"{{ url('user') }}/"+user_id,'name','Update');
    }

    function customValidations(parent_id) {
        var company = $('#'+parent_id+' input[name=company]');
        var first_name = $('#'+parent_id+' input[name=first_name]');
        var last_name = $('#'+parent_id+' input[name=last_name]');
        var phone = $('#'+parent_id+' input[name=phone]');
        var email = $('#'+parent_id+' input[name=email]');

        if(company.val() == '') {
            company.focus();
            return false;
        } else if(first_name.val() == '') {
            first_name.focus();
            return false;
        } else if(last_name.val() == '') {
            last_name.focus();
            return false;
        } else if(phone.val() == '') {
            phone.focus();
            return false;
        } else if(email.val() == '') {
            email.focus();
            return false;
        }
        return true;
    }
</script>