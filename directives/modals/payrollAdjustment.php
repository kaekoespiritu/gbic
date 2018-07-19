<div class="modal fade" id="attendanceAdjustment">
  <div class="modal-dialog modal-lg" role="document" style="width: 100% !important; height: 100% !important; margin: 10; padding: 10;">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Attendance Adjustment</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Insert date picker and call attendance row here -->
          Please the date to adjust: <input type="text" onchange="attAdjustment(this.value)" id="dateValue">
          <div id="adjustmentFields">
          </div>
          </div>
      </div>
    </div>
  </div>
</div>