<?php 
    include('config.php');
    include(root.'common/header.php');
?>

<!-- BEGIN: Content-->
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Bed</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-xl-12">

            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" id="btnnew"><i class="bi bi-star me-1"></i> New Bed</button>
                </div>
                <div class="card-body">
                    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                        <div class="dataTable-top">
                            <div class="dataTable-search">
                                <input type="hidden" name="ser">
                                <input class="dataTable-input" placeholder="Search..." type="search" id="searching">
                            </div>
                        </div>
                        <div id="show_table" class="dataTable-container">

                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>

        <!-- new Modal -->
        <div class="modal fade text-left" id="btnnewmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel25"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <label class="modal-title text-text-bold-600" id="myModalLabel25">Add New Bed</label>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="frmsave" method="POST">
                        <input type="hidden" name="action" value="save" />
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="usr">Bed Number</label>
                                <input type="text" required class="form-control" name="number" placeholder="Enter Bed Number">
                            </div>
                            <div class="form-group">
                                <label for="usr">Ward Name</label>
                                <select required class="form-control" name="ward">
                                    <option value="">Select Ward</option>
                                    <?=load_ward()?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn grey btn-outline-danger" data-bs-dismiss="modal"><i
                                    class="la la-close"></i>Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="la la-edit"></i>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- edit Modal -->
        <div class="modal fade text-left" id="btneditmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel25"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <label class="modal-title text-text-bold-600" id="myModalLabel25">Edit Bed Information</label>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="frmedit" method="POST">
                        <input type="hidden" name="action" value="edit" />
                        <input type="hidden" name="eaid" value="">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="usr">Bed Number</label>
                                <input type="text" required class="form-control" name="enumber" placeholder="Enter Bed Number">
                            </div>
                            <div class="form-group">
                                <label for="usr">Ward</label>
                                <select required class="form-control" name="eward">
                                    <option value="">Select Ward</option>
                                    <?=load_ward()?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn grey btn-outline-danger" data-bs-dismiss="modal"><i
                                    class="la la-close"></i>Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="la la-edit"></i>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include(root.'common/footer.php'); ?>

<script>
$(document).ready(function() {
    function load_pag() {
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'bed_action.php' ?>",
            data: {
                action: 'show',
                search: search
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }

    load_pag();

    $(document).on('click', '.page-link', function() {
        var pageid = $(this).data('page_number');
        load_pag(pageid);
    });

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_pag();
    });

    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    $(document).on("click", "#btnnew", function() {
        $("#btnnewmodal").modal("show");
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'bed_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    $("[name='number']").val("");
                    $("[name='ward']").val("");
                    swal("Success", "Save data is successful.", "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error", data, "error");
                }
            }
        });
    });

    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var number = $(this).data("number");
        var ward = $(this).data("ward");
        $("[name='eaid']").val(aid);
        $("[name='enumber']").val(number);
        $("[name='eward']").val(ward);
        $("#btneditmodal").modal("show");
        
    });

    $("#frmedit").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btneditmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'bed_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Success", "Edit data is successful.", "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error", data, "error");
                }
            }
        });
    });

    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var number = $(this).data("number");
        swal({
                title: "Delete?",
                text: "Are you sure delete!",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'bed_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
                        number: number
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_pag();
                            swal.close();
                        } else {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                    }
                });
            });
    });
});
</script>