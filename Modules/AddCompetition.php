<?php include("../includeSafety.php"); ?>

<title class="title">New Competition</title>
<form>
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Title*:</label>
      <input type="text" class="form-control" id="validationDefault01" value="Comp 2021" required>
    </div>
    </div>
    <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault02">Date*:</label>
      <input type="date" class="form-control" id="validationDefault02" required>
     </div>
   
  </div>
 
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="validationTextarea">Descripttion:</label>
      <textarea type="text" class="form-control" id="validationTextarea" placeholder="Comp description"></textarea>
    </div>
  </div>
  <button class="btn btn-primary" type="submit">Add</button>
</form>