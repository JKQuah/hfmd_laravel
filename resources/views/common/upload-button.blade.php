<!-- Upload data file button -->
<button type="button" class="btn btn-warning rounded-circle btn-lg add_file_btn" data-toggle="modal" data-target="#exampleModalCenter"><span class="material-icons add_icon">add</span></button>
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Upload Data Excel File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please make sure the column name <strong>must</strong> be exactly the same as the given example</p>
                <table class="table table-bordered table-sm table-responsive-lg">
                    <thead>
                        <tr>
                            <th>case_id</th>
                            <th>states</th>
                            <th>district</th>
                            <th>gender</th>
                            <th>birthday</th>
                            <th>notification_date</th>
                            <th>onset_date</th>
                            <th>status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>3867</td>
                            <td>Johor</td>
                            <td>Johor Bharu</td>
                            <td>Male</td>
                            <td>2016-05-20</td>
                            <td>2017-12-17</td>
                            <td>2017-12-18</td>
                            <td>infected</td>
                        </tr>
                    </tbody>
                </table>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="dataFileUpload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    <label class="custom-file-label" for="dataFileUpload">Choose file..</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning">Upload</button>
            </div>
        </div>
    </div>
</div>