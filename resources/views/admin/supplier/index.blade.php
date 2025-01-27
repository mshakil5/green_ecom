@extends('admin.layouts.admin')

@section('content')

<!-- Main content -->
<section class="content" id="newBtnSection">
    <div class="container-fluid">
      <div class="row">
        <div class="col-2">
            <button type="button" class="btn btn-secondary my-3" id="newBtn">Add new</button>
        </div>
      </div>
    </div>
</section>
<!-- /.content -->


<section class="content mt-3" id="addThisFormContainer">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"  id="cardTitle">Add new data</h3>
                    </div>
                    <div class="card-body">
                        <div class="ermsg"></div>
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" class="form-control" id="codeid" name="codeid">
                            <div class="row">
                                <div class="col-sm-6 d-none">
                                    <div class="form-group">
                                        <label>Code<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" id="id_number" name="id_number" placeholder="Enter code">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter phone">
                                    </div>
                                </div>
                                <div class="col-sm-6 d-none">
                                    <div class="form-group">
                                        <label>Vat Reg</label>
                                        <input type="number" class="form-control" id="vat_reg" name="vat_reg" placeholder="Enter vat reg">
                                    </div>
                                </div>
                                <div class="col-sm-6 d-none">
                                    <div class="form-group">
                                        <label>Contract Date</label>
                                        <input type="date" class="form-control" id="contract_date" name="contract_date" placeholder="Enter contract date">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter address"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-none">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <textarea class="form-control" id="company" name="company" rows="3" placeholder="Enter company"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-10">
                                    <div class="form-group">
                                        <label for="feature-img">Supplier Image</label>
                                        <input type="file" class="form-control-file" id="image" accept="image/*">
                                        <img id="preview-image" src="#" alt="" style="max-width: 300px; width: 100%; height: auto; margin-top: 20px;">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" id="addBtn" class="btn btn-secondary" value="Create">Create</button>
                        <button type="submit" id="FormCloseBtn" class="btn btn-default">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payModalLabel">Pay Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="payForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="paymentAmount">Payment Amount</label>
                        <input type="number" class="form-control" id="paymentAmount" name="paymentAmount" placeholder="Enter payment amount">
                    </div>
                    <div class="form-group">
                        <label for="paymentNote">Payment Note</label>
                        <textarea class="form-control" id="paymentNote" name="paymentNote" rows="3" placeholder="Enter payment note"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Pay</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="content" id="contentContainer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">All Data</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th>Address</th>
                                    <th>Transactions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td>{{ $data->phone }}</td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>{{ $data->balance }}</span>
                                            <button class="btn btn-sm btn-warning pay-btn" data-id="{{ $data->id }}" data-supplier-id="{{ $data->id }}">Pay</button>
                                        </div>
                                      <input type="hidden" id="supplierId" name="supplierId">  
                                    </td>
                                    <td>{{ $data->address }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info transaction-btn" data-id="{{ $data->id }}" data-name="{{ $data->name }}">
                                            Transactions
                                        </button>
                                    </td>
                                    <td>
                                        <a id="EditBtn" rid="{{ $data->id }}">
                                            <i class="fa fa-edit" style="color: #2196f3; font-size:16px;"></i>
                                        </a>
                                        <a id="deleteBtn" rid="{{ $data->id }}">
                                            <i class="fa fa-trash-o" style="color: red; font-size:16px;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="transactionsModal" tabindex="-1" role="dialog" aria-labelledby="transactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionsModalLabel">Transactions for <span id="supplierName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="transactionsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')

<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

<script>
  $(document).ready(function () {
      $("#addThisFormContainer").hide();
      $("#newBtn").click(function(){
          clearform();
          $("#newBtn").hide(100);
          $("#addThisFormContainer").show(300);

      });
      $("#FormCloseBtn").click(function(){
          $("#addThisFormContainer").hide(200);
          $("#newBtn").show(100);
          clearform();
      });

      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      //
      var url = "{{URL::to('/admin/supplier')}}";
      var upurl = "{{URL::to('/admin/supplier-update')}}";

      $("#addBtn").click(function(){

          //create
          if($(this).val() == 'Create') {
              var form_data = new FormData();
              form_data.append("id_number", $("#id_number").val());
              form_data.append("name", $("#name").val());
              form_data.append("email", $("#email").val());
              form_data.append("phone", $("#phone").val());
              form_data.append("vat_reg", $("#vat_reg").val());
              form_data.append("address", $("#address").val());
              form_data.append("company", $("#company").val());
              form_data.append("contract_date", $("#contract_date").val());

              var featureImgInput = document.getElementById('image');
                if(featureImgInput.files && featureImgInput.files[0]) {
                    form_data.append("image", featureImgInput.files[0]);
                }

              $.ajax({
                url: url,
                method: "POST",
                contentType: false,
                processData: false,
                data:form_data,
                success: function (d) {
                    if (d.status == 303) {
                        $(".ermsg").html(d.message);
                    }else if(d.status == 300){
                        swal({
                            text: "Created Successfully",
                            icon: "success",
                            button: {
                                text: "OK",
                                className: "swal-button--confirm"
                            }
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr, status, error) {
                   console.error(xhr.responseText);
                }
            });
          }
          //create  end

          //Update
          if($(this).val() == 'Update'){
              var form_data = new FormData();
              form_data.append("id_number", $("#id_number").val());
              form_data.append("name", $("#name").val());
              form_data.append("email", $("#email").val());
              form_data.append("phone", $("#phone").val());
              form_data.append("vat_reg", $("#vat_reg").val());
              form_data.append("address", $("#address").val());
              form_data.append("company", $("#company").val());
              form_data.append("contract_date", $("#contract_date").val());

              var featureImgInput = document.getElementById('image');
                if(featureImgInput.files && featureImgInput.files[0]) {
                    form_data.append("image", featureImgInput.files[0]);
                }

              form_data.append("codeid", $("#codeid").val());
              
              $.ajax({
                  url:upurl,
                  type: "POST",
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  data:form_data,
                  success: function(d){
                    //   console.log(d);
                      if (d.status == 303) {
                          $(".ermsg").html(d.message);
                          pagetop();
                      }else if(d.status == 300){
                          swal({
                            text: "Updated Successfully",
                            icon: "success",
                            button: {
                                text: "OK",
                                className: "swal-button--confirm"
                            }
                        }).then(() => {
                            location.reload();
                        });
                      }
                  },
                  error: function(xhr, status, error) {
                   console.error(xhr.responseText);
                }
              });
          }
        //Update  end
      });
      //Edit
      $("#contentContainer").on('click','#EditBtn', function(){
          $("#cardTitle").text('Update this data'); 
          codeid = $(this).attr('rid');
          info_url = url + '/'+codeid+'/edit';
          $.get(info_url,{},function(d){
              populateForm(d);
              pagetop();
          });
      });
      //Edit  end

      //Delete
      $("#contentContainer").on('click','#deleteBtn', function(){
            if(!confirm('Sure?')) return;
            codeid = $(this).attr('rid');
            info_url = url + '/'+codeid;
            $.ajax({
                url:info_url,
                method: "GET",
                type: "DELETE",
                data:{
                },
                success: function(d){
                    if(d.success) {
                        swal({
                          text: "Deleted",
                          icon: "success",
                          button: {
                              text: "OK",
                              className: "swal-button--confirm"
                          }
                      }).then(() => {
                          location.reload();
                      });
                    }
                },
                error:function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // console.log(d);
                }
            });
        });
      //Delete  
      function populateForm(data){
          $("#id_number").val(data.id_number);
          $("#name").val(data.name);
          $("#email").val(data.email);
          $("#phone").val(data.phone);
          $("#vat_reg").val(data.vat_reg);
          $("#address").val(data.address);
          $("#company").val(data.company);
          $("#contract_date").val(data.contract_date);
          $("#codeid").val(data.id);
          $("#addBtn").val('Update');
          $("#addBtn").html('Update');
          $("#addThisFormContainer").show(300);
          $("#newBtn").hide(100);

           var featureImagePreview = document.getElementById('preview-image');
            if (data.image) { 
                featureImagePreview.src = '/images/supplier/' + data.image;
            } else {
                featureImagePreview.src = "#";
            }

      }
      function clearform(){
          $('#createThisForm')[0].reset();
          $("#addBtn").val('Create');
          $("#addBtn").html('Create');
          $('#preview-image').attr('src', '#');
          $("#cardTitle").text('Add new data');
      }
  });
</script>

<script>
    $(document).ready(function () {
        $("#contentContainer").on('click', '.pay-btn', function () {
            var id = $(this).data('id');
            var supplierId = $(this).data('supplier-id');

            $('#payModal').modal('show');
            $('#payForm').off('submit').on('submit', function (event) {
                event.preventDefault();

                var paymentAmount = $('#paymentAmount').val();
                var paymentNote = $('#paymentNote').val();
                // console.log('supplierId:', supplierId);

                $.ajax({
                    url: '{{ URL::to('/admin/pay') }}',
                    method: 'POST',
                    data: {
                        id: id,
                        supplier_id: supplierId,
                        amount: paymentAmount,
                        note: paymentNote,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        alert(response.message);
                        $('#payModal').modal('hide');
                        location.reload();
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        $('#payModal').on('hidden.bs.modal', function () {
            $('#paymentAmount').val('');
            $('#paymentNote').val('');
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("#image").change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $("#preview-image").attr("src", e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#example1').on('click', '.transaction-btn', function() {
            var supplierId = $(this).data('id');
            var supplierName = $(this).data('name');
            $('#supplierName').text(supplierName);
            $('#transactionsTable tbody').empty();
            $('#transactionsModal').modal('show');
            $.ajax({
                url: '{{ URL::to('/admin/supplier/transactions') }}',
                method: 'POST',
                data: {
                    supplier_id: supplierId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.transactions.length > 0) {
                        response.transactions.forEach(function(transaction) {
                            var newRow = '<tr>' +
                                '<td>' + moment(transaction.date).format('DD-MM-YYYY') + '</td>' +
                                '<td>' + transaction.amount + '</td>' +
                                '<td>' + transaction.note + '</td>' +
                                '</tr>';
                            $('#transactionsTable tbody').append(newRow);
                        });
                    } else {
                        $('#transactionsTable tbody').append('<tr><td colspan="3">No transactions found</td></tr>');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection