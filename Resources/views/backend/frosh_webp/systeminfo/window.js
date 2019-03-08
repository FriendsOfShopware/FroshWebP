//{block name="backend/systeminfo/view/main/window" append}
Ext.override(Shopware.apps.Systeminfo.view.main.Window, {
    createTabPanel: function () {
        var tabPanel = this.callParent(arguments);

        tabPanel.add(Ext.create('Shopware.apps.Systeminfo.view.systeminfo.WebpEncoders'));

        return tabPanel;
    }
});
//{/block}