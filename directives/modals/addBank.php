<!-- MODAL for adding bank-->
			<div class="modal fade" role="dialog" id="addBank">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11 col-lg-11">Add new bank</h4>
				        <button type="button" class="close col-md-1 col-lg-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- form for adding SITES -->
				    <form method="POST" action="logic_options_addBank.php">
					    <div class="modal-body">
				     		<input type="text" class="form-control" id="bankNameValidate" name="bank_name" placeholder="Name of new bank">
				     		<div align="left">
				     			<h3 align="left">Color:</h3>
				     			<input type="radio" value="94D0EA" name="color[]"> <span class="swatch_1"></span> <span class="color_font">Light Cornflower Blue</span><br>
				     			<input type="radio" value="FAEE8A" name="color[]"> <span class="swatch_2"></span> <span class="color_font">Flavescent</span><br>
				     			<input type="radio" value="A6C26D" name="color[]"> <span class="swatch_3"></span> <span class="color_font">Middle Green Yellow</span><br>
				     			<input type="radio" value="E3866E" name="color[]"> <span class="swatch_4"></span> <span class="color_font">Middle Red</span><br>
				     			<input type="radio" value="F79A5E" name="color[]"> <span class="swatch_5"></span> <span class="color_font">Sandy Brown</span><br>
				     			<input type="radio" value="FE938C" name="color[]"> <span class="swatch_6"></span> <span class="color_font">Tulip</span><br>
				     			<input type="radio" value="E6B89C" name="color[]"> <span class="swatch_7"></span> <span class="color_font">Pale Gold</span><br>
				     			<input type="radio" value="EAD2AC" name="color[]"> <span class="swatch_8"></span> <span class="color_font">Desert Sand</span><br>
				     			<input type="radio" value="9CAFB7" name="color[]"> <span class="swatch_9"></span> <span class="color_font">Pewter Blue</span><br>
				     			<input type="radio" value="4281A4" name="color[]"> <span class="swatch_10"></span> <span class="color_font">Steel Blue</span>
				     		</div>
				     	</div>
				     	<div class="modal-footer">
					        <button type="submit" class="btn btn-primary">Save changes</button>
					    </div>
				  	</form>
			    </div>
			  </div>
			</div>