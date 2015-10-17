$(function(){
   $('.list-action-delete').click(function(){
        var ids = $('.list-items:checked').map(function () {
          return this.value;
        }).get();
        //console.log(ids);
        
        if(ids.length > 0) {
            if(confirm('Are you sure you want to remove ' + ids.length + (ids.length == 1 ? ' item' : ' items?'))) {
                $.post(window.location.href + "delete", {items:ids}, function() {
                    document.location.reload();
                });
            }
        }else {
            alert('Please select item to remove');
        }
   });
   
   $('.list-action-refresh').click(function(){
        document.location.reload();
   });
}); 