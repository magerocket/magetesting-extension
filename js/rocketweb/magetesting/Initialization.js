// Create message and wizard blocks objects
MagentoMessageObj = new MagentoMessage();

WizardBlocksObj = new Blocks();

// Set observer
Event.observe(window, 'load', function() {

    $$('.wizard_block_hidden').each(function(blockObj) {
        WizardBlocksObj.prepare(blockObj);
    });

});