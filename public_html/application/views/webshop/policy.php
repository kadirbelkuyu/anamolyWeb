<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0">
             <iframe width="100%" height="100%" seamless="seamless"  id="aboutIframe" style="border:none; height:300px"  src="<?php echo REMOTE_URL."/index.php/apppages/policy/"._language(); ?>" >
             </iframe>
            </div>
        </div>
    </div>
</div>
<script>
var frame = document.getElementById("aboutIframe");
var body = document.body,
    html = document.documentElement;

var height = Math.max( body.scrollHeight, body.offsetHeight,
                       html.clientHeight, html.scrollHeight, html.offsetHeight );
frame.style.height=( height - (64 * 2) )+'px';
</script>
<style>
::-webkit-scrollbar {
    width: 0px;  /* Remove scrollbar space */
    background: transparent;  /* Optional: just make scrollbar invisible */
}
/* Optional: show position indicator in red */
::-webkit-scrollbar-thumb {
    background: transparent;
}
</style>
