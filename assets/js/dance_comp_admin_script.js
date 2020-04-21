/**
 * Created by Emil on 21.04.2020.
 */
( function( wp ) {
    var checked = jQuery("input[name*='visible']:checked").length;
    //alert(checked);

    if (checked == 0) {
        jQuery(".dance_comp-condition").hide();
        return false;
    } else {
        jQuery(".dance_comp-condition").show();
        return true;
    }

} )(
    window.wp
);