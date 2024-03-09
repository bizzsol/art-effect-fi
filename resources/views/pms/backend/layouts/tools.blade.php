<div class="modal" id="POdetailsModel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Purchase Order</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" id="body">

            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Requisition Details Modal Start -->
<div class="modal" id="requisitionDetailModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Requisition Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="tableData">

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- Requisition Details Modal End -->

<!-- Notification Modal Start -->
<div class="modal" id="notificationModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<!-- Notification Modal End -->

<!-- Notification Modal Start -->
<div class="modal" id="sampleModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<!-- Notification Modal End -->

<script>
    (function ($) {
        "use script";

        const showPODetails = () => {
            $('.showPODetails').on('click', function () {

                $.ajax({
                    url: $(this).attr('data-src'),
                    type: 'get',
                    dataType: 'json',
                    data: '',
                })
                .done(function(response) {

                    if (response.result=='success') {
                        $('#POdetailsModel').find('#body').html(response.body);
                        $('#POdetailsModel').find('.modal-title').html(`Purchase Order Details`);
                        $('#POdetailsModel').modal('show');
                    }

                })
                .fail(function(response){
                    notify('Something went wrong!','error');
                });
            });
        };
        showPODetails();

        const showRequistionDetails = () => {
            $('.showRequistionDetails').on('click', function (e) {
                $.ajax({
                    url: e.target.getAttribute('data-src'),
                    type: 'get',
                    dataType: 'json',
                    data: '',
                    success: function (response) {
                         $('#requisitionDetailModal').find('#tableData').html(response);
                         $('#requisitionDetailModal').find('.modal-title').html(`Requisition Details`);
                         $('#requisitionDetailModal').modal('show');
                    }
                });
            });
        };
        showRequistionDetails();

    })(jQuery);

 
    function purchaseOrderDetails(element) {
        $.ajax({
            url: element.attr('data-src'),
            type: 'get',
            dataType: 'json',
            data: '',
        })
        .done(function(response) {
            if (response.result=='success') {
                $('#POdetailsModel').find('#body').html(response.body);
                $('#POdetailsModel').find('.modal-title').html(`Purchase Order Details`);
                $('#POdetailsModel').modal('show');
            }
        })
        .fail(function(response){
            notify('Something went wrong!','error');
        });
    }

    function requistionDetails(element) {
        $.ajax({
            url: element.attr('data-src'),
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (response) {
                $('#requisitionDetailModal').find('#tableData').html(response);
                $('#requisitionDetailModal').find('.modal-title').html(`Requisition Details`);
                $('#requisitionDetailModal').modal('show');
            }
        });
    }
</script>