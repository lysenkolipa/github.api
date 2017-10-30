$(document).ready(function() {
    $('#htmlSet').on('click', function() {
        copyRules();
    });

    $('body').on('click', 'input.remove', function () {
        $(this).parent().remove();
    });

    function copyRules(){
        $('#rules').append($('.htmlSetter').html());
    }
    copyRules();


    $('form').on('submit',function(e) {
        e.preventDefault();

        var $form = $(this);
        $.ajax({
            type: 'POST',
            url: 'code.core',
            data: $form.serialize()
        }).done(function(data) {
//                alert('sent');
//                console.log(data);
            $('form').hide();
            getJSON(data);

        }).fail(function() {
            console.log('fail');
        });

        function getJSON (data){
            var obj = eval(data.items);
            // var output = '<ul class="searchresult">';
            //
            // for(var i=0; i < obj.length; i++) {
            //     output += '<li>';
            //     output += '<h4> full_name: '+obj[i].full_name+'</h4>';
            //     output += '<a href="'+obj[i].html_url+'">link to repository </a>';
            //     output += '<p> size: ' +obj[i].size +'</p>';
            //     output += '<p> forks: '+obj[i].forks+'</p>';
            //     output += '<p> followers: '+obj[i].followers+'</p>';
            //     output += '<p> watchers: '+obj[i].watchers+'</p>';
            //     output += '</li>';
            // }
            // output += '</ul>';
            // $('#list').html(output);
            var size=null;
            var forks = null;
            var followers = null;
            for(var i=0; i < obj.length; i++) {
                size += obj[i].size;
                forks += obj[i].forks;
                followers += obj[i].watchers;
            }
            $('#size').html(size);
            $('#forks').html(forks);
            $('#followers').html(followers);
            $('#repos').html(obj.length-1);

            $('#table').DataTable({
                data: obj,
                columns:[
                    {data:"full_name"},
                    {data:"forks"},
                    {data:"url"},
                    {data:"watchers"}
                ]
            });
            var grafic = [];
            $.each(obj, function(key, value){
                var test = {name: this.name, forks: this.forks, watchers: this.watchers};
                grafic.push(test);
            })
            console.log(grafic);
            Morris.Bar({
                element: 'chart',
                data: grafic,
                // The name of the data record attribute that contains x-values.
                xkey: 'name',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['forks', 'watchers'],
                labels: ['forks', 'watchers']

            })

        }

    });
});
