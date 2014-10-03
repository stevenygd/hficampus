<link href="/css/entrance/login.css" type="text/css">
<script type="text/javascript" src="/js/entrance/register.js"></script>
<div class="center">
	<h6>reset pword</h6>
    <form class="enform" id="reset" action="<?=site_url('account/pwreset')?>" method="post">
        <div class="endiv">
            <label>pword</label>
    		<input type="text" id="reset_npw" name="pword" value="ygd1995" /></br>
            <label>pword confirmed</label>
    		<input type="text" id="reset_npw_comfirm" name="pword" value="ygd1995" /></br>
        </div>
        <input type="submit" id="reset_submit" value="SUBMIT" />
    </form>
</div>
