<?php 
/**
* This Module gives the user with the necessary permissions the ability to change information of disciplines, add and delete disciplines within certain competitions
*/

include("../includeSafety.php"); ?>
<title class="title">New Discipline</title>
<form>
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Title*:</label>
      <input type="text" class="form-control" id="validationDefault01" value="Comp 2021" required>
    </div>
    </div>
    <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault02">Rounds*:</label>
      <input type="number" class="form-control" id="validationDefault02" required>
     </div>
   
  </div>
 
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="validationTextarea">Description:</label>
      <textarea type="text" class="form-control" id="validationTextarea" placeholder="Comp description"></textarea>
    </div>
  </div>
  <button class="btn btn-primary" type="submit">Add</button>
</form>