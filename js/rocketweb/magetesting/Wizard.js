Wizard = Class.create();
Wizard.prototype = {

    initialize : function(currentStatus, currentStep, hiddenSteps)
    {
        this.currentStatus = currentStatus;

        this.steps = {};
        this.steps.current = currentStep;
        this.steps.hidden = hiddenSteps || [];
        this.steps.nicks = [];
    },

    scrollPageToTop: function()
    {
        if (location.href[location.href.length-1] != '#') {
            setLocation(location.href+'#');
        } else {
            setLocation(location.href);
        }
    },

    setConstants: function(data)
    {
        data = eval(data);
        for (var i=0;i<data.length;i++) {
            eval('this.'+data[i][0]+'=\''+data[i][1]+'\'');
        }
    },

    addStep : function(step, stepContainerId)
    {
        var self = WizardObj;

        if (self.steps.hidden.indexOf(step) != -1) {
            return;
        }

        self.steps[step] = stepContainerId;
        self.steps.nicks.push(step);
        self.renderStep(step);
    },

    getNextStepByNick : function(step)
    {
        var self = WizardObj;
        var stepIndex = self.steps.nicks.indexOf(step);

        if (stepIndex == -1) {
            return null;
        }

        var nextStepNick = self.steps.nicks[stepIndex + 1];

        if (typeof nextStepNick == 'undefined') {
            return null;
        }

        return nextStepNick;
    },

    renderStep : function(step)
    {
        var self = WizardObj;
        var stepContainerId = self.steps[step];

        if (typeof stepContainerId == 'undefined') {
            return;
        }

        // Render step subtitle
        var stepNumber = self.steps.nicks.indexOf(step) + 1;
        var subtitle = '[' + WizardConfig.text.step_word + ' ' + stepNumber + ']';

        $(stepContainerId).writeAttribute('subtitle', subtitle);

        if (typeof $$('#' + stepContainerId + ' span.subtitle')[0] != 'undefined') {
            $$('#' + stepContainerId + ' span.subtitle')[0].innerHTML = subtitle;
        }

        $$('#'+stepContainerId+' .step_completed').each(function(obj) {
            obj.hide();
        });
        $$('#'+stepContainerId+' .step_process').each(function(obj) {
            obj.hide();
        });
        $$('#'+stepContainerId+' .step_incomplete').each(function(obj) {
            obj.hide();
        });

        var stepIndex = self.steps.all.indexOf(step);
        var currentStepIndex = self.steps.all.indexOf(self.steps.current);

        if (currentStepIndex >= stepIndex) {
            $(stepContainerId).show();
        } else {
            $(stepContainerId).hide();
        }

        if ((currentStepIndex > stepIndex) ||
            self.currentStatus == self.STATUS_COMPLETED) {
            $$('#'+stepContainerId+' .step_completed').each(function(obj) {
                obj.show();
            });
            $$('#'+stepContainerId+' .step_container_buttons').each(function(obj) {
                obj.remove();
            });
            $(stepContainerId).writeAttribute('style','background-color: #F2EFEF !important; border-color: #008035 !important;');
        } else {
            $$('#'+stepContainerId+' .step_process').each(function(obj) {
                obj.show();
            });
            if (window.completeStep == 0) {
                $$('#'+stepContainerId+' .step_incomplete').each(function(obj) {
                    obj.show();
                });
            }
        }
    },

    processAccountStep : function(stepUrl, step)
    {
        var self = WizardObj;

        var nextStepNick = self.getNextStepByNick(step);

        new Ajax.Request( stepUrl,
        {
            method: 'post',
            parameters: {
                username: $$('#magetesting_username')[0].getValue(),
                apikey: $$('#magetesting_apikey')[0].getValue(),
                next_step: nextStepNick
            },
            asynchronous: true,
            onSuccess: (function(transport) {
                var response = transport.responseText.evalJSON();

                if (response.type == 'error') {
                    this.scrollPageToTop();
                    MagentoMessageObj.clearAll();
                    return MagentoMessageObj.addError(response.message);
                }

                if (nextStepNick) {
                    this.steps.current = nextStepNick;
                    MagentoMessageObj.clearAll();
                    self.renderStep(step);
                    return self.renderStep(nextStepNick);
                }

            }).bind(this)
        }) 
    },

    processStorebackupStep : function(stepUrl, step)
    {
        var self = WizardObj;

        var nextStepNick = self.getNextStepByNick(step);

        new Ajax.Request( stepUrl,
        {
            method: 'post',
            parameters: {
                storebackup: $$('#magetesting_storebackup')[0].getValue(),
                next_step: nextStepNick 
            },
            asynchronous: true,
            onSuccess: (function(transport) {
                var response = transport.responseText.evalJSON();

                if (response.type == 'error') {
                    this.scrollPageToTop();
                    MagentoMessageObj.clearAll();
                    return MagentoMessageObj.addError(response.message);
                }

                if (nextStepNick) {
                    this.steps.current = nextStepNick;
                    MagentoMessageObj.clearAll();
                    self.renderStep(step);
                    return self.renderStep(nextStepNick);
                }

            }).bind(this)
        })
    },

    processDbbackupStep : function(stepUrl, step)
    {
        var self = WizardObj;

        var nextStepNick = self.getNextStepByNick(step);

        new Ajax.Request( stepUrl,
        {
            method: 'post',
            parameters: {
                dbbackup: $$('#magetesting_dbbackup')[0].getValue(),
                next_step: nextStepNick
            },
            asynchronous: true,
            onSuccess: (function(transport) {
                var response = transport.responseText.evalJSON();

                if (response.type == 'error') {
                    this.scrollPageToTop();
                    MagentoMessageObj.clearAll();
                    return MagentoMessageObj.addError(response.message);
                }

                if (nextStepNick) {
                    this.steps.current = nextStepNick;
                    MagentoMessageObj.clearAll();
                    self.renderStep(step);
                    return self.renderStep(nextStepNick);
                }

            }).bind(this)
        })
    },
    
    processConnectionStep : function(stepUrl, step)
    {
        var self = WizardObj;

        var nextStepNick = self.getNextStepByNick(step);

        new Ajax.Request( stepUrl,
        {
            method: 'post',
            parameters: {
                protocol: $$('#magetesting_protocol')[0].getValue(),
                host: $$('#magetesting_host')[0].getValue(),
                port: $$('#magetesting_port')[0].getValue(),
                username: $$('#magetesting_username')[0].getValue(),
                password: $$('#magetesting_password')[0].getValue(),
                root: $$('#magetesting_root')[0].getValue(),
                next_step: nextStepNick
            },
            asynchronous: true,
            onSuccess: (function(transport) {
                var response = transport.responseText.evalJSON();

                if (response.type == 'error') {
                    this.scrollPageToTop();
                    MagentoMessageObj.clearAll();
                    return MagentoMessageObj.addError(response.message);
                }

                var nextStepNick = self.getNextStepByNick(step);

                if (nextStepNick) {
                    // redirect as it's easiest way to have import data filled in next step 
                    setLocation(WizardConfig.url.home);
                }

            }).bind(this)
        })
    },

    processImportStep : function(stepUrl, step, callback)
    {
        var self = WizardObj;

        new Ajax.Request( stepUrl,
            {
                method: 'post',
                parameters: {
                    import: 1
                },
                asynchronous: true,
                onSuccess: (function(transport) {
                    var response = transport.responseText.evalJSON();

                    if (response.type == 'error') {
                        this.scrollPageToTop();
                        MagentoMessageObj.clearAll();
                        return MagentoMessageObj.addError(response.message);
                    }

                    var nextStepNick = self.getNextStepByNick(step);

                    if (nextStepNick) {
                        return;
                    }
                    
                    this.currentStatus = self.STATUS_COMPLETED;
                    
                    if (typeof callback == 'function') {
                        callback();
                    }
                }).bind(this)
            })
    },

    generateBackup : function(type)
    {
        var self = WizardObj;

        new Ajax.Request( WizardConfig.url.backup,
            {
                method: 'get',
                parameters: {
                    type: type,
                    backup_name: 'magetesting'
                },
                asynchronous: true,
                onSuccess: (function(transport) {
                    var response = transport.responseText.evalJSON();

                    if (response.type == 'error') {
                        this.scrollPageToTop();
                        MagentoMessageObj.clearAll();
                        return MagentoMessageObj.addError(response.message);
                    }

                    if (!!response.redirect_url) {
                        setLocation(WizardConfig.url.home);
                    }

                }).bind(this)
            })
    },

    findRootPath : function(url)
    {
        var self = WizardObj;

        new Ajax.Request( url,
            {
                method: 'post',
                parameters: {
                    protocol: $$('#magetesting_protocol')[0].getValue(),
                    host: $$('#magetesting_host')[0].getValue(),
                    port: $$('#magetesting_port')[0].getValue(),
                    username: $$('#magetesting_username')[0].getValue(),
                    password: $$('#magetesting_password')[0].getValue()
                },
                asynchronous: true,
                onSuccess: (function(transport) {
                    var response = transport.responseText.evalJSON();
                    
                    MagentoMessageObj.clearAll();

                    if (response.type == 'error') {
                        this.scrollPageToTop();
                        return MagentoMessageObj.addError(response.message);
                    }

                    if (response.root_path) {
                        $$('#magetesting_root')[0].setValue(response.root_path);
                    }

                    if (!!response.redirect_url) {
                        setLocation(WizardConfig.url.home);
                    }

                }).bind(this)
            })
    }

}