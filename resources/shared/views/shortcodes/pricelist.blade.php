<div class="pricelist">
    @if($priceLists->isEmpty())
    {{ __('No menus available') }}
    @else
    @if($priceLists->count() === 1)
    @include('shared.views.shortcodes.pricelist.' . $priceLists->first()->type, ['priceList' => $priceLists->first()])
    @else

    <TabGroup>
        <TabList>
            @foreach($priceLists as $priceList)
            <Tab>{{ $priceList->post_title }}</Tab>
            @endforeach
        </TabList>
        <TabPanels>
            @foreach($priceLists as $priceList)
            <TabPanel>
                @include('shared.views.shortcodes.pricelist.' . $priceList->type, ['priceList' => $priceList])
            </TabPanel>
            @endforeach
        </TabPanels>
    </TabGroup>

    @endif
    @endif
</div>
