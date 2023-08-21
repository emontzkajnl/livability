<form role="search" method="get" class="search-form">
	<label for="keyword">Search…</label>
	<input type="search" name="keyword" id="keyword" class="search-field">
	<!-- <input type="search" id="search-form-1" placeholder="Search..." class="search-field" value="" name="s"> -->
	<!-- <input type="submit" class="search-submit" value="" /> -->
	<button type="submit" class="search-submit" style="background:#7dc244;"><i class="fa fa-search"></i></button>
<div id="datafetch"></div>
</form>
<style>
	#datafetch {
		max-height:200px;
		padding:0px 10px;
		background:#FFF;
		border-bottom:#111 1px solid;
		border-left:#111 1px solid;
		border-right:#111 1px solid;
    width: 300px;
    overflow-y: auto;
    z-index: 90;
    position: fixed;
    margin-top: 44px;
	}
	#datafetch a {
		margin:4px 0px;
		padding: 0px;
    font-size: 14px;
		font-family: 'Jost';
	}
</style>