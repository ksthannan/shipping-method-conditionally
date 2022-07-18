
"use strict";
jQuery(window).ready(function(){
function ship_hidden (){
    var hidden_id = jQuery('.all_shipping_methods ul#activate_ship').data('ship');
    setTimeout(() => {
        jQuery('.wc-shipping-zone-method-rows > tr').each(function(){
            var instance_id = jQuery(this).data('id');
            if(instance_id !== undefined){
                
                var item_title = jQuery(this).find('td.wc-shipping-zone-method-title > a').text();
                var item_zone = jQuery('#zone_name').val();
                var new_title = item_title + ' - ' + instance_id;

                jQuery(this).find('td.wc-shipping-zone-method-title > a').text(new_title);

                var status = {
                    active_id: "",
                    instance_id: "",
                    link_title: "Click to activate",
                    text: "Activate",
                    value: "deactive",
                    class: "activate"
                };

                jQuery(hidden_id).each(function(index){
                    if(hidden_id[index] == instance_id){
                        status.instance_id = instance_id;
                        status.active_id = hidden_id[index];
                        status.value = 'active';
                        status.text = 'deactivate';
                        status.class = 'deactivate';
                        status.link_title = 'Click to deactivate';
                    }
                });

                console.log(status)

                var st_class = status.class;
                var link_title_text = status.link_title;
                var link_title_text = status.link_title;
                var status_text = status.text;

                jQuery('.all_shipping_methods ul#activate_ship').append('<li class="'+st_class+'"><span>'+ item_title + ' - (#' + instance_id + ') ' + '</span><a title="'+link_title_text+'" href="admin.php?page=woo-shipping-show-hide&instance_id='+instance_id+'&zone='+item_zone+'&method_title='+item_title+'"> ' +status_text+'</a></li>');

                status.instance_id = '';
                status.active_id = '';
                status.value = 'deactive';
                status.text = 'Activate';
                status.class = 'activate';
                status.link_title = 'Click to activate';

            }else{
                jQuery('ul#activate_ship').attr('style', 'display:none;');
            }
        });


    }, 1500);
}




ship_hidden();
   


});