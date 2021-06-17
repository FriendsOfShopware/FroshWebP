//{block name="backend/systeminfo/view/systeminfo/filelist" append}
//{namespace name=backend/systeminfo/view}

Ext.define('Shopware.apps.Systeminfo.view.systeminfo.WebpEncoders', {
    extend: 'Ext.grid.Panel',
    ui: 'shopware-ui',
    alias: 'widget.systeminfo-main-webp-encoders',
    region: 'center',
    autoScroll: true,
    title: 'Webp-Support',
    border: 0,

    initComponent: function(){
        this.columns = this.getColumns();

        this.store = Ext.create('Ext.data.Store', {
            fields: [
                'name',
                'available'
            ],
            proxy: {
                type: 'ajax',
                url: '{url action="webpencoders"}',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true
        });

        this.callParent(arguments);
    },

    getColumns: function(){
        var me = this;

        return [
            {
                header: '{s name="file_grid/column/name"}Name{/s}',
                dataIndex: 'name',
                flex: 1
            },{
                header: '{s name="file_grid/column/required"}Available{/s}',
                dataIndex: 'available',
                width: '65px',
                renderer: me.renderStatus
            }
        ];
    },

    /**
     * Function to render the status. 1 = a green tick, everything else = a red cross
     * @param value The value of the field
     */
    renderStatus: function(value){
        if(value == 1){
            return Ext.String.format('<div style="height: 16px; width: 16px" class="sprite-tick"></div>')
        }else{
            return Ext.String.format('<div style="height: 16px; width: 16px" class="sprite-cross"></div>')
        }
    }
});
//{/block}
