<script type="text/javascript">
    
(function ($){
    "use script";

    var menus = <?php echo json_encode(getThisPageMenus()); ?>;
    var menu_count = Object.keys(menus).length;;

       let mouseEvent;
        const catchMouseEvent = () => {
            $('main').mousedown( function(e) {
                let mousex = event.clientX;
                let mousey = event.clientY;
                mouseEvent = window.event;
                if(mouseEvent.which === 3){
                    e.preventDefault();
                    showCustomMenu({ x:mousex, y:mousey  }, e);
                }else if(mouseEvent.which === 1){
                    if(mouseEvent.path != undefined && mouseEvent.path[1] != undefined){
                        let content = mouseEvent.path[1];
                        if(!content.classList.contains('contextMenu-content')){
                         $('#contextMenu').remove();
                        }
                    }
                }
            });
        }
        catchMouseEvent();

        const showCustomMenu = (position, e) => {
            document.querySelector('body').addEventListener("contextmenu", function (e){
                e.preventDefault();
            }, false);

            if(menu_count > 0){
                $('#contextMenu').remove();
                let body = document.querySelector('body');
                body.style = 'position: relative;';

                let menu = document.createElement('div')
                menu.id = 'contextMenu';
                menu.className = 'contextMenu-content'
                menu.style = `position: absolute; left: ${position.x}px; top: ${position.y}px; z-index: 1000000; width: 300px; background: #E5E5E5; border-radious: 5px;`
                
                let menuItemReload = document.createElement('div')
                menuItemReload.className = 'item cMReload p-2'
                menuItemReload.innerHTML = '<i class="las la-redo-alt" style="font-size: 16px;"></i>&nbsp;Reload'
                menu.appendChild(menuItemReload)

                $.each(menus, function(index, val) {
                    if(val['permission'] != ''){
                        let item = document.createElement('div')
                        item.className = 'item contextmenu-items p-2'
                        item.innerHTML = '<i class="'+(val['icon'])+'" style="font-size: 16px;"></i>&nbsp;'+(val['name']);
                        item.setAttribute("data-src", index);
                        menu.appendChild(item);
                    }
                });

                // if($('.datatable-exportable').length > 0){
                //     let menuColumnVisibility = document.createElement('div')
                //     menuColumnVisibility.className = 'item cMColumnVisibility p-2'
                //     menuColumnVisibility.innerHTML = '<i class="las la-eye-slash" style="font-size: 16px;"></i>&nbsp;Column Visibility'
                //     menu.appendChild(menuColumnVisibility)
                // }

                body.appendChild(menu);

                let reloadBtn = document.querySelector('.cMReload');
                reloadBtn.addEventListener('click', reloadWindow);

                // let cMColumnVisibilityBtn = document.querySelector('.cMColumnVisibility');
                // cMColumnVisibilityBtn.addEventListener('click', menuColumnVisibilityAction);

                let dataRow = document.querySelectorAll('.contextmenu-items');
                Array.from(dataRow).map(item => {
                    item.addEventListener('click', (e)=>{
                        e.preventDefault();
                        location.href=item.getAttribute('data-src');
                    })
                });
            }
        }

        const reloadWindow = () => {
            window.location.reload();
        }

        // const menuColumnVisibilityAction = () => {
        //     var table = $('.datatable-exportable').DataTable();
        //     table.buttons('.buttons-colvis').trigger();
        // }

    })(jQuery);

</script>