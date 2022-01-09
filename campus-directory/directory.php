<script type="text/javascript" src="https://www.mtu.edu/search/includes/directory.js"></script>
<style>
	#results a {
		cursor: pointer;
	}

	.input-group {
		border-top: 1px solid;
		clear: both;
		padding-top: 10px;
	}

	.input-group:first-of-type {
		border-top: 0;
	}

	.form-group {
		float: left;
		margin-left: 20px;
	}

	.search_bool {
			min-width: 15%;
		}

		.search_field {
			min-width: 15%;
		}

		.search_method {
			min-width: 15%;
		}

		.search_text {
			min-width: 32%;
		}

		.search_remove {
			margin-top: 20px;
		}

		#copy-1 .search_field {
			min-width: 80px;
		}

		#copy-1 .search_method {
			min-width: 80px;
		}

		#copy-1 .search_text {
			min-width: 90%;
		}

	@media only screen and (min-width: 500px) {
		.input-group {
			border-top: 0;
			padding-top: 0;
		}

		.search_bool {
			max-width: 6%;
			min-width: 1%;
		}

		#copy-1 .search_field {
			min-width: 160px;
		}

		#copy-1 .search_method {
			min-width: 160px;
		}

		#copy-1 .search_text {
			min-width: 410px;
		}
	}

</style>

<form id="search-form" name="search-form" method="post" class="validate" novalidate>
<input class="sp_field" name="sp_field" size="50" type="text" value="" aria-hidden="true" title="Skip">

<div id="copy-1" class="input-group">

<div id="bool" style="display: none;" class="form-group search_bool"><label for="search_bool" class="control-label">Relation</label><select class="form-control" name="bool[]" id="search_bool">
	<option value="and">And</option>
	<option value="or">Or</option>
	<option value="andnot">And Not</option>
	<option value="ornot">Or Not</option>
</select>
</div>

<div id="search-field" class="form-group search_field"><label for="search_field" class="control-label">Search Field</label><select class="form-control" name="field[]" id="search_field">
	<option value="fname">First Name</option>
	<option value="lname">Last Name</option>
	<option value="name" selected="selected">Name</option>
	<option value="email">Email Address</option>
	<option value="user">User Name</option>
</select>
</div>

<div id="search-method" class="form-group search_method"><label for="search_method" class="control-label">Search Method</label><select class="form-control" name="method[]" id="search_method">
	<option value="contains">Contains</option>
	<option value="sw">Starts With</option>
	<option value="ew">Ends With</option>
	<option value="sl">Sounds Like</option>
	<option value="is">Is</option>
</select>
</div>

<div class="form-group search_text"><label for="text" class="control-label">Search Text</label><input type="text" name="text[]" id="text" placeholder="" class="gettext form-control" ></div>

</div>

<!--<table style="min-width: 100%;" class="ou-boxed-sections-even-columns">
<tbody>
<tr id="copy-1" class="input-group">
<td>
<div id="bool" style="display: none;" class="form-group"><label for="search_bool" class="control-label">Relation</label><select class="form-control" name="bool[]" id="search_bool">
	<option value="and">And</option>
	<option value="or">Or</option>
	<option value="andnot">And Not</option>
	<option value="ornot">Or Not</option>
</select>
</div>
</td>
<td>
<div id="search-field" class="form-group"><label for="search_field" class="control-label">Search Field</label><select class="form-control" name="field[]" id="search_field">
	<option value="fname">First Name</option>
	<option value="lname">Last Name</option>
	<option value="name" selected="selected">Name</option>
	<option value="email">Email Address</option>
	<option value="user">User Name</option>
</select>
</div>
</td>
<td>
<div id="search-method" class="form-group"><label for="search_method" class="control-label">Search Method</label><select class="form-control" name="method[]" id="search_method">
	<option value="contains">Contains</option>
	<option value="sw">Starts With</option>
	<option value="ew">Ends With</option>
	<option value="sl">Sounds Like</option>
	<option value="is">Is</option>
</select>
</div>
<td>
<div  class="form-group"><label for="text" class="control-label">Search Text</label><input type="text" name="text[]" id="text" placeholder="" class="gettext form-control" ></div>
</td>
<br />
</tr>
</tbody>
</table>-->
	<div class="clearer"></div>
<ul class="list-spread three" role="region" aria-label="Advanced Controls">
	<li><a tabindex="0" id="add"  href="javascript:void(0);" aria-label="Add Criteria" class="button-blank" role="button">Add Criteria</a></li>
	<li><a tabindex="0" id="reset" href="javascript:void(0);" aria-label="New Search" class="button-blank" role="button">New Search</a></li>
	<li><a tabindex="0" id="submit" href="javascript:void(0);" aria-label="Search" class="button" role="button">Search</a></li>
</ul>
<!--
<table  style="min-width: 100%;" class="ou-boxed-sections-even-columns">
<tbody>
<tr>
<td><a id="add" class="button-blank">Add Criteria</a></td>
<td><a id="reset" class="button-blank">New Search</a></td>
<td><a id="submit" class="button">Search</a>	</td>
</tr>
</tbody>
</table>
<h4 class="wysiwyg-spacer">&nbsp;</h4>-->
</form>
<div id="results"></div>



