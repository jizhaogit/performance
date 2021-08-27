<x-side-layout>
    <div class="row">
        <div class="col-12 col-sm-6">
            <h1>Hi {{ Auth::user()->name }}</h1>
        </div>
        <div class="col-12 col-sm-6 text-right">
            <x-button id="add-goal-to-library-btn" tooltip="Create a goal for your employees to use in their own profile." tooltipPosition="bottom">
                Add Goal to Library
            </x-button>
            <x-button id="share-my-goals-btn" tooltip="Choose which of your goals are visible to your employees" tooltipPosition="bottom">
                Share My Goals
            </x-button>
        </div>
    </div>
    <div class="col-md-8"> @include('my-team.partials.tabs')</div>
    @yield('tab-content')
    @include('my-team.partials.share-my-goals-modal')
    @include('my-team.partials.add-goal-to-library-modal')
    @include('conversation.partials.add-conversation-modal')
    @include('conversation.partials.view-conversation-modal')
    @include('my-team.partials.employee-profile-sharing-modal')
    @include('my-team.partials.employee-excused-modal')
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.min.css') }}">
    @endpush
    @push('js')
    <script src="{{ asset('js/bootstrap-multiselect.min.js')}} "></script>
    <script>
        (function () {
            $(document).on('click', '.btn-submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/conversation'
                    , type: 'POST'
                    , data: $('#conversation_form').serialize()
                    , success: function(result) {
                        if (result.success) {
                            window.location.reload();
                        }
                    }
                    , error: function(error) {
                        var errors = error.responseJSON.errors;
                        $('.error-date-alert').hide();
                        $('.text-danger').each(function(i, obj) {
                            $('.text-danger').text('');
                        });
                        Object.entries(errors).forEach(function callback(value, index) {
                            var className = '.error-' + value[0];
                            $(className).text(value[1]);
                            if (value[0] === 'date') {
                                $('.error-date-alert').show();
                            }
                        });
                    }
                });
            });
            $(document).on('click', '#share-my-goals-btn', function () {
                $("#shareMyGoalsModal").modal('show');
            });
            $(document).on('click', '#add-goal-to-library-btn', function () {
                $("#addGoalToLibraryModal").modal('show');
            });
            $(".search-users").each(function() {
                const goalId = $(this).data('goal-id');
                $(this).multiselect({
                    allSelectedText: 'All Direct Report',
                    selectAllText: 'All Direct Report',
                    includeSelectAllOption: true
                });
            });

            $(".share-with-users").select2({
                width: '100%',
                ajax: {
                    url: '/users',
                    dataType: 'json',
                    data: function (params) {
                        const query = {
                            search: params.term,
                            page: params.page || 1
                        };
                        return query;
                    },
                    processResults: function (response, params) {
                        return {
                            results: $.map(response.data.data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            }),
                            pagination: {
                                more: response.data.current_page !== response.data.last_page
                            }
                        }
                    }
                }
            });

            $(".items-to-share").multiselect({
                allSelectedText: 'All',
                selectAllText: 'All',
                includeSelectAllOption: true
            });
            var currentUserForModal  = null;
            $(document).on('show.bs.modal', '#employee-profile-sharing-modal', function (e) {
                const userId = $(e.relatedTarget).data('user-id')
                $(this).find('#share-profile-form').find('[name=shared_id]').val(userId);
                $modal = $(this);
                currentUserForModal = userId;
                loadSharedProfileData(userId, $modal);
            });
            function loadSharedProfileData(userId, $modal) {
                $.ajax({
                    url: "{{route('my-team.profile-shared-with', 'xxx')}}".replace('xxx', userId),
                    success: function (response) {
                        $modal.find('.shared-with-list').html(response);
                        $(".items-to-share-edit").multiselect({
                            allSelectedText: 'All',
                            selectAllText: 'All',
                            includeSelectAllOption: true
                        });
                    }
                });
            }
            $(document).on('click', ".edit-field", function (e) {
                $('.view-mode').removeClass("d-none");
                $('.edit-mode').addClass("d-none");
                $viewArea = $(this).parents('.view-mode');
                $editArea = $(this).parents('td').find('.edit-mode');
                $editArea.removeClass("d-none");
                $viewArea.addClass("d-none");
            });

            $(document).on('submit', '.share-profile-form-edit', function (e) {
                $form = $(this);
                const data = $form.serializeArray();
                data.push({name: 'action', value: this.submitted});
                $.ajax({
                    method: 'POST',
                    url: $form.attr('action'),
                    data: data,
                    success: function () {
                        loadSharedProfileData(currentUserForModal, $form.parents('.modal'));
                    }
                });
                e.preventDefault();
            });

            $(document).on('submit', '#share-profile-form', function (e) {
                e.preventDefault();
                const $form = $(this);
                $.ajax({
                    url: $form.attr('action'),
                    type : 'POST',
                    data: $form.serialize(),
                    success: function (result) {
                        if(result.success){
                            // window.location.href= '/goal';
//                            $("#employee-profile-sharing-modal").modal('hide');
                            alert("Successfully shared");
                            window.location.reload(true);
                        }
                    },
                    beforeSend: function() {
                        $form.find('.text-danger').each(function(i, obj) {
                            $('.text-danger').text('');
                        });
                    },
                    error: function (error){
                        var errors = error.responseJSON.errors;

                        Object.entries(errors).forEach(function callback(value, index) {
                            var className = '.error-' + value[0];
                            $form.find(className).text(value[1]);
                        });
                    }
                });
            });

            $(document).on('change', '.is-shared', function (e) {
                let confirmMessage = "Making this goal private will hide it from all employees. Continue?";
                if (this.checked) {
                    confirmMessage = "Sharing this goal will make it visible to the selected employees. Continue?"
                }
                if (!confirm(confirmMessage)) {
                    this.checked = !this.checked;
                    e.preventDefault();
                    return;
                }
                $(this).parents("label").find("span").html(this.checked ? "Shared" : "Private");
                const goalId = $(this).data('goal-id');
                $("#search-users-" + goalId).multiselect(this.checked ? 'enable' : 'disable');
            });
            $("#participant_id").select2();

            $(document).on('click', '.conversation-link', function(e) {
                const id = $(this).data("id");
                const userId = $(this).data("user-id");
                if (id === 'new') {
                    // Open new modal
                    $("#conversation_form").find("[name='owner_id']").val(userId);
                    return;
                }
                else {
                    // conversation_id = e.target.getAttribute('data-id');
                    // debugger;
                    updateConversation(id);
                }
            });

            function updateConversation(conversation_id) {
                $.ajax({
                    url: '/conversation/' + conversation_id
                    , success: function(result) {
                        $('#conv_participant_edit').val('');
                        $('#conv_participant').val('');
                        $('#conv_title').text(result.topic.name);
                        $('#conv_title_edit').val(result.topic.name);
                        $('#conv_date').text(result.c_date);
                        $('#conv_date_edit').val(result.date);
                        $('#conv_time').text(result.c_time);
                        $('#conv_time_edit').val(result.time);
                        $('#conv_comment').text(result.comment);
                        $('#conv_comment_edit').text(result.comment);
                        $('#info_comment1').text(result.info_comment1);
                        $('#info_comment1_edit').text(result.info_comment1);
                        $('#info_comment2').text(result.info_comment2);
                        $('#info_comment2_edit').text(result.info_comment2);
                        $('#info_comment3').text(result.info_comment3);
                        $('#info_comment3_edit').text(result.info_comment3);
                        $('#info_comment4').text(result.info_comment4);
                        $('#info_comment4_edit').text(result.info_comment4);
                        $('#info_comment5').text(result.info_comment4);
                        $('#info_comment5_edit').text(result.info_comment4);

                        if(!!$('#unsign-off-form').length) {
                            $('#unsign-off-form').attr('action', $('#unsign-off-form').data('action-url').replace('xxx', conversation_id));
                        }
                        $('#questions-to-consider').html('');
                        if(result.topic.id == 4){
                            $('#info_to_capture').removeClass('d-none');
                        }else{
                            $('#info_to_capture').addClass('d-none');
                        }

                        result.questions?.forEach((question) => {
                            $('#questions-to-consider').append('<li>' + question + '</li>');
                        });
                        $('#template-title').text(result.topic.name + ' Template');
                        // $('#conv_participant_edit').next(".select2-container").hide();

                        var participants = '';
                        $.each(result.topics, function(key, value) {
                            var selected = '';
                            if (value.id == result.conversation_topic_id) {
                                selected = 'selected';
                            }
                            $('#conv_title_edit').append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                        });
                        $.each(result.conversation_participants, function(key, value) {
                            var data = {
                                id: value.id
                                , text: value.participant.name
                            , };
                            var comma = ', ';
                            if (result.conversation_participants.length == (key + 1)) {
                                comma = '';
                            }
                            participants = participants + value.participant.name + comma;
                            var newOption = new Option(value.participant.name, value.id, true, true);
                            $('#conv_participant_edit').append(newOption).trigger('change');
                            $('#conv_participant_edit').trigger({
                                type: 'select2:select'
                                , params: {
                                    data: data
                                }
                            });
                        });
                        $('#conv_participant').text(participants);
                    }
                    , error: function(error) {
                        var errors = error.responseJSON.errors;
                    }
                });
            }
            $(document).on('click', '.conversation-link', function () {
                if($(this).data("id") === 'new') {
                    $("#participant_id").val($(this).data("user-id"));
                    $("#participant_id").change();
                }
            });

            $(document).on('click', '.share-profile-btn', function() {
                const userName = $(this).data("user-name");
                $("#employee-profile-sharing-modal").find(".user-name").html(userName);
            });

            $(document).on('click', '.excused-btn', function() {
                const userName = $(this).data("user-name");
                $("#employee-excused-modal").find(".user-name").html(userName);
            });

        })();
    </script>
    @endpush
</x-side-layout>
