<div
    ref="sidebar"
    class="duration-80 fixed top-[60px] z-[10002] h-full w-[200px] border-gray-200 bg-white pt-4 transition-all group-[.sidebar-collapsed]/container:w-[70px] dark:border-gray-800 dark:bg-gray-900 max-lg:hidden ltr:border-r rtl:border-l"
    @mouseover="handleMouseOver"
    @mouseleave="handleMouseLeave"
>
    <div class="journal-scroll h-[calc(100vh-100px)] overflow-hidden group-[.sidebar-collapsed]/container:overflow-visible">
        <nav class="sidebar-rounded grid w-full gap-2">
            <!-- Navigation Menu -->
            @foreach (menu()->getItems('admin') as $menuItem)
                <div class="px-4 group/item {{ $menuItem->isActive() ? 'active' : 'inactive' }}">
                    <a
                        class="flex gap-2 p-1.5 items-center cursor-pointer hover:rounded-lg"
                        :class="{
                            'bg-brandColor rounded-lg': '{{ $menuItem->isActive() }}' === 'active',
                            'hover:bg-gray-100 hover:dark:bg-gray-950': '{{ $menuItem->isActive() }}' !== 'active'
                        }"
                        :href="'{{ ! in_array($menuItem->getKey(), ['settings', 'configuration']) && $menuItem->haveChildren() }}' === '1' ? 'javascript:void(0)' : '{{ $menuItem->getUrl() }}'"
                        data-menu-key="{{ $menuItem->getKey() }}"
                        @mouseenter="hoveringMenu = '{{ $menuItem->getKey() }}'"
                        @mouseleave="hoveringMenu = ''"
                        @click="isMenuActive = !isMenuActive"
                    >
                        <span
                            :class="[
                                '{{ $menuItem->getIcon() }}',
                                'text-2xl',
                                {'text-white': '{{ $menuItem->isActive() }}' === 'active'}
                            ]"
                        ></span>

                        <div
                            class="flex-1 flex justify-between items-center font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden group"
                            :class="{
                                'text-white': '{{ $menuItem->isActive() }}' === 'active',
                                'text-gray-600 dark:text-gray-300': '{{ $menuItem->isActive() }}' !== 'active'
                            }"
                        >
                            <span class="menu-text">{{ core()->getConfigData('general.settings.menu.'.$menuItem->getKey()) ?? $menuItem->getName() }}</span>
                            <i
                                v-if="'{{ ! in_array($menuItem->getKey(), ['settings', 'configuration']) && $menuItem->haveChildren() }}'"
                                class="icon-right-arrow rtl:icon-left-arrow invisible text-2xl group-hover/item:visible"
                                :class="{'text-white': '{{ $menuItem->isActive() }}' === 'active'}"
                            ></i>
                        </div>
                    </a>

                    <!-- Submenu -->
                    <div
                        v-if="'{{ ! in_array($menuItem->getKey(), ['settings', 'configuration']) && $menuItem->haveChildren() }}'"
                        class="absolute top-0 flex-col bg-gray-100 ltr:left-[200px] rtl:right-[199px]"
                        v-show="isMenuActive && hoveringMenu === '{{ $menuItem->getKey() }}'"
                    >
                            <div class="sidebar-rounded fixed z-[1000] h-full min-w-[140px] max-w-max bg-white pt-4 after:-right-[30px] dark:border-gray-800 dark:bg-gray-900 max-lg:hidden ltr:border-r rtl:border-x">
                                <div class="journal-scroll h-[calc(100vh-100px)] overflow-hidden">
                                    <nav class="grid w-full gap-2">
                                        @foreach ($menuItem->getChildren() as $subMenuItem)
                                            <div class="px-4 group/item {{ $menuItem->isActive() ? 'active' : 'inactive' }}">
                                                <a
                                                    :href="'{{ $subMenuItem->getUrl() }}'"
                                                    class="flex gap-2.5 p-2 items-center cursor-pointer hover:rounded-lg peer"
                                                    :class="{
                                                        'bg-brandColor rounded-lg': '{{ $subMenuItem->isActive() }}' === 'active',
                                                        'hover:bg-gray-100 hover:dark:bg-gray-950': '{{ $subMenuItem->isActive() }}' !== 'active'
                                                    }"
                                                >
                                                    <span
                                                        class="font-medium whitespace-nowrap"
                                                        :class="{
                                                            'text-white': '{{ $subMenuItem->isActive() }}' === 'active',
                                                            'text-gray-600 dark:text-gray-300': '{{ $subMenuItem->isActive() }}' !== 'active'
                                                        }"
                                                    >
                                                        {{ core()->getConfigData('general.settings.menu.'.$subMenuItem->getKey()) ?? $subMenuItem->getName() }}
                                                    </span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </nav>
                                </div>
                            </div>
                        </div>
                </div>
            @endforeach
        </nav>
    </div>
</div>
