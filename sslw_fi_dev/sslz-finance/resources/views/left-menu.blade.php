<div class="iq-sidebar">
    <input type="hidden" value="{{ url('/') }}" id="base_url">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="{{ url('/') }}">
            <img src="{{ asset(session()->get('system-information')['icon']) }}" class="img-fluid" alt="{{ session()->get('system-information')['organization'] }}">
            <span style="font-size: 24px">{{ session()->get('system-information')['name'] }}</span>
        </a>
        <div class="iq-menu-bt-sidebar">
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="las la-ellipsis-h"></i></div>
                    <div class="hover-circle"><i class="las la-ellipsis-v"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div id="sidebar-scrollbar">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                @forelse($menus as $key => $menu)
                    @canany(json_decode($menu->slug,true))
                    @php
                        $activeSubmenuNumber=count($menu->activeSubMenu);
                        $firstLiActiveClass='';
                        if (Request()->path()==$menu->url){
                            $firstLiActiveClass='active';
                        }
                    @endphp
                    <li class="iq-menu-main {{ $firstLiActiveClass }}">
                        @php
                            $menuToggle = '';
                            $collapsed = '';
                            $dropDownIcon = '';
                            $url = url($menu->url);
                            if ($activeSubmenuNumber>0){
                                $menuToggle = 'collapse';
                                $collapsed = 'collapsed';
                                $dropDownIcon = "las la-angle-right iq-arrow-right";
                                $url = '#'.\Str::slug( $menu->name.$menu->id);
                            }
                        @endphp
                        <a href="{{$url}}" class="iq-waves-effect {{$collapsed}}" data-toggle="{{$menuToggle}}" target="{{$menu->open_new_tab == \App\Models\PmsModels\Menu\Menu::OPEN_NEW_TAB?'_blank':''}}" aria-expanded="false"><i class="{{$menu->icon_class}}"></i><span>{{ __($menu->name) }}</span><i class="{{$dropDownIcon}}"></i></a>
                        @if($activeSubmenuNumber>0)
                            <ul id="{{\Str::slug( $menu->name.$menu->id)}}" class="iq-submenu {{$menuToggle}}" data-parent="#iq-sidebar-toggle">
                                @foreach($menu->activeSubMenu as $subMenu)
                                    @canany(json_decode($subMenu->slug,true))
                                        <li class="{{ Request()->path() == $subMenu->url?'active':'' }}">
                                            <a  href="{{url($subMenu->url)}}"><i class="las la-arrow-right" target="{{$subMenu->open_new_tab==\App\Models\PmsModels\Menu\SubMenu::OPEN_NEW_TAB?'_blank':''}}"></i>{{ __($subMenu->name) }}</a>
                                        </li>
                                    @endcanany
                                @endforeach
                            </ul>
                        @endif
                    </li>
                    @endcanany
                @empty
                    <li> No Menu Data Found</li>
                @endforelse
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>