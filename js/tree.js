var data_tree;

function is_array (a) {
    return (typeof a == "object") && (a instanceof Array);
}

global_str = new Array();
data_tree = new Array();
my_tree = "";
GetParent_var = "";


function ClearTreeArchitecture(){
    
    $.post('TreeOperation',{CLEAR_TABLE: 'set'},null);
    
    $('#tree_arch').fadeIn(300);
    $('#tree_arch').delay(2000).fadeOut(300);
    
}

function GetNodeByNodeId(global_array,tree,districts){
    
    //взять всех детей
    var node_children = new Array();
    node_children = GetChildren(tree,global_array.id);

    for(var i=0; i<node_children.length; i++){
        
        var dis = { label: GetDistrictTitle(districts,node_children[i]), id: node_children[i], children: []};
        global_array.children.push(dis); 
        
    }//к текущему дали детей
    
    for(var i=0; i<global_array.children.length; i++){

        if( HasChildren(tree,global_array.children[i]) ){

            GetNodeByNodeId(global_array.children[i],tree,districts);

        }
        
    }

}

function GetParent(tree,district){
    
    for (i=0; i<tree.length; i++){
        //alert(tree[i].ChildDistictId);
        if(tree[i].ChildDistictId == district.id){
            return tree[i].DistrictId;
        }
    }
    return false;
}

function GetChildren(tree, district){
    
    var children = new Array();
    
    for (i=0; i<tree.length; i++){
        
        if(tree[i].DistrictId == district){
            children.push(tree[i].ChildDistictId);
        }
    }
    
    return children;
    
}

function HasParent(tree, district){

    for (i=0; i<tree.length; i++){
        
        if(tree[i].ChildDistictId == district.id){
            return false;
        }
    }
    return true;
    
}

function HasChildren(tree, district){

    for (var i=0; i<tree.length; i++){
        
        if(tree[i].DistrictId == district.id){
            return true;
        }
    }
    return false;
    
}

function HasChildrenModify(tree, district){

    for (i=0; i<tree.length; i++){
        
        if(tree[i].DistrictId == district.id){
            return true;
        }
    }
    return false;
    
}

function GetDistrictTitle(districts,id){

    for (i=0; i<districts.length; i++){
        
        if(districts[i].id == id){
            return districts[i].Title;
        }
    }
    return false;
    
}

function RemoveDistrict(DISTR_ID){
    
    $.post('ajax.php',{REMOVE_DISTRICT: 'set', districtID: DISTR_ID },function(data){
    });
    
}

function RemoveStopWord(WORD_ID){
    
    $.post('ajax.php',{REMOVE_STOPWORD: 'set', wordID: WORD_ID },function(data){
    });
    
}

$(document).ready(function(){
     
    $('body').on('click','.remove_district',function(){
        
        d_id = $(this).parent().data('district-id');
        RemoveDistrict(d_id);
        $(this).parent().parent().remove();
        
    });
    
    $('body').on('click','.remove_stop_word',function(){
        
        s_id = $(this).parent().data('stop-id');
        RemoveStopWord(s_id);
        $(this).parent().parent().remove();
        
    });
     
    $('#tree_succ').fadeOut(0);
    $('#tree_arch').fadeOut(0);
    
    $('#ClearTree').click(function(){
        
        ClearTreeArchitecture();
        
    });
    
    $.post('ajax.php',{GET_ALL_DISTRICTS: 'set'},function(data){
        
        districts = $.parseJSON(data);
        
        $.post('TreeOperation.php',{GET_DISTRICTS_TREE: 'set'},function(tree){
            
            my_tree = $.parseJSON(tree);
            
            $.each(districts,function(indx,district){
                
//                if(indx >= 20){
//                    return false;
//                }//if
                
                
                
                //добавим только глобальные
                if (HasParent(my_tree, district)){
                    var dis = {label: district.Title, id: district.id, children: []};
                    data_tree.push(dis); 
                }
                
                
                 
            });
            
            $.each(districts,function(indx,district){
            
//                if(indx >= 20){
//                    return false;
//                }//if
                if (HasParent(my_tree, district)){
                    
                    child = HasChildren(my_tree,district);
                    
                    
                    if(child){
                        for (i=0; i<data_tree.length; i++){
                            if (data_tree[i].id == district.id){
                                
                                GetNodeByNodeId(data_tree[i],my_tree,districts);
                                break;
                                
                            }
                        }

                    }
                    
                }
            
            });
            
                
            $('#tree1').tree({
                data: data_tree,
                //saveState: true,
                dragAndDrop: true
            });
            
            $(".nano").nanoScroller();
            
        });


    });

    $('#SaveMyTreeDistricts').click(function(){
        
        global_str = new Array();
        $.post('TreeOperation',{CLEAR_TABLE: 'set'},null);
        //AroundTree($('ul.jqtree-tree'));
        AroundTreeModify($('ul.jqtree-tree'));

        
        $('#tree_succ').fadeIn(300);
        $('#tree_succ').delay(2000).fadeOut(300);
        //$(location).reload();

          
    });

});


function AroundTreeModify(parrent){
    var in_recur = false;

    var children = $(parrent).children();
    for(var i=0; i<$(children).length; i++){
        //проходим по детям
        if($(children[i]).hasClass('jqtree-folder')){//если у ребенка есть дети
            
            //нашли родителя у которого есть дети 
            var parent_title = $(children[i]).children().first().text();
            parent_title = parent_title.replace('►','');
            parent_title = parent_title.replace('▼','');
            
            var children_ul = $(children[i]).children().last().children();
            //найдем его детей
            var parents_children = new Array();
            
            
            for(var j=0; j<$(children_ul).length; j++){
                
                //alert('Дети '+ $(children_ul[j]).children().first().text());
                parents_children[j] = $(children_ul[j]).children().first().text(); 
                
                parents_children[j] = parents_children[j].replace('►','');
                parents_children[j] = parents_children[j].replace('▼','');
                
                //добавим каждого ребенка в базу
                $.post('TreeOperation',{ADD_CHILD: 'set',parrent: parent_title,child: parents_children[j]},function(data){
                });
                 
            }
            
            //alert('РОДИТЕЛЬ '+parent_title+' ДЕТИ '+parents_children);
            
            //еще раз обойдем детей, для выявления детей дейтей
            for(var j=0; j<$(children_ul).length; j++){
                
                if($(children_ul[j]).hasClass('jqtree-folder')){//если у него есть дети передадим детей
                    
                    //AroundTreeModify($(children[i]).children());
                    //AroundTreeModify($(children_ul[j]).parent()());
                    in_recur = true;
                    
                }
            }
            if(in_recur){
                //alert('в рекурсию');
                AroundTreeModify($(children[i]).children());
                //alert("вышли из рекурсии   "+'РОДИТЕЛЬ '+parent_title+' ДЕТИ '+parents_children);
            }
        }
        
    }
    
        
}

function AroundSelectedBranch(parrent){
    
    var in_recur = false;

    var children = $(parrent).children();
    for(var i=0; i<$(children).length; i++){
        //проходим по детям
        if($(children[i]).hasClass('jqtree-folder')){//если у ребенка есть дети
            
            //нашли родителя у которого есть дети 
            var parent_title = $(children[i]).children().first().text();
            parent_title = parent_title.replace('►','');
            parent_title = parent_title.replace('▼','');
            
            sessionStorage['selected_li'] = sessionStorage['selected_li'] + parent_title + ";";

            var children_ul = $(children[i]).children().last().children();
            //найдем его детей
            var parents_children = new Array();
            
            
            for(var j=0; j<$(children_ul).length; j++){
                
                //если у детей нет детей запишем, если нет не запшем (позже запишет как батьку)
                if($(children_ul[j]).hasClass('jqtree-folder') == false){
                    parents_children[j] = $(children_ul[j]).children().first().text(); 

                    parents_children[j] = parents_children[j].replace('►','');
                    parents_children[j] = parents_children[j].replace('▼','');

                    sessionStorage['selected_li'] = sessionStorage['selected_li'] + parents_children[j] + ";";

                }
                 
            }

            
            //еще раз обойдем детей, для выявления детей дейтей
            for(var j=0; j<$(children_ul).length; j++){
                
                if($(children_ul[j]).hasClass('jqtree-folder')){//если у него есть дети передадим детей
                    
                    in_recur = true;
                    
                }
            }
            if(in_recur){
                AroundSelectedBranch($(children[i]).children());
            }
        }
        
    }
    
}