function showMember(id){
    
    var name = $('#button' + id).text();
    
    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: 'name=' + name,
        dataType: 'json',
        success: function(member){
            //image setup
            $('#memberImage').prop('src',relPath + member.Image);
            
            //table data
            var html = "";
            
            html+= html += '<tr>\n\
                            <td>' + member.RoleDisplayName + '</td>\n\
                            <td>' + member.RoleDescription + '</td>\n\
                         </tr>';
           
           delete member.RoleDescription;
           delete member.RoleDisplayName;
           delete member.Image;
           
            $.each(member, function(key,value){
                if(value.substring(0,4) == "http"){
                    value = '<a href="' + value + '">' + value + '</a>';
                }
                
                    html += '\
                            <tr>\n\
                                <td>' + key + '</td>\n\
                                <td>' + value + '</td>\n\
                            </tr>';
            });
            
            $('#memberTable').html(html);
        }   
    });
}