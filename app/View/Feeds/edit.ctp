<div class="feed form">
<?php echo $this->Form->create('Feed');?>
    <fieldset>
        <legend><?php echo __('Edit MISP Feed');?></legend>
        <?php
            if (!empty(Configure::read('Security.disable_local_feed_access'))) {
                echo sprintf(
                    '<p class="red bold">%s</p>',
                    __('Warning: local feeds are currently disabled by policy, to re-enable the feature, set the Security.allow_local_feed_access flag in the server settings. This setting can only be set via the CLI.')
                );
            }
            echo '<p>' . __('Edit a new MISP feed source.') . '</p>';
            echo $this->Form->input('enabled', array(
                'type' => 'checkbox'
            ));
            echo $this->Form->input('caching_enabled', array(
                'type' => 'checkbox'
            ));
    ?>
        <div class="input clear"></div>
    <?php
            echo $this->Form->input('lookup_visible', array(
                'type' => 'checkbox'
            ));
            echo $this->Form->input('name', array(
                    'div' => 'input clear',
                    'placeholder' => __('Feed name'),
                    'class' => 'form-control span6',
            ));
            echo $this->Form->input('provider', array(
                    'div' => 'input clear',
                    'placeholder' => __('Name of the content provider'),
                    'class' => 'form-control span6'
            ));
            $options = array('network' => 'Network');
            if (empty(Configure::read('Security.disable_local_feed_access'))) {
                $options['local'] = 'Local';
            }
            echo $this->Form->input('input_source', array(
                    'div' => 'input clear',
                    'options' => $options,
                    'class' => 'form-control span6'
            ));
            ?>
            <div id="HeadersDiv"<?php echo $this->request->data['Feed']['input_source'] == 'file' ? 'style="display:none;"' : '';?>>
        <?php
                echo $this->Form->input('headers', array(
                    'label' => __('Any headers to be passed with requests (for example: Authorization)'),
                    'div' => 'clear',
                    'class' => 'input-xxlarge',
                    'type' => 'textarea',
                    'placeholder' => __('Line break separated list of headers in the "headername: value" format')
                ));
        ?>
            <div>
                <span id="basicAuthFormEnable" class="btn btn-inverse quick-popover" style="line-height:10px; padding: 4px 4px;"><?php echo __('Add Basic Auth');?></span>
                <div id="basicAuthForm" class="quick-form" style="display:none;">
                        <fieldset>
                            <div class="input">
                                <label for="BasicAuthUsername"><?php echo __('Username');?></label>
                                <input class="form-control" type="text" id="BasicAuthUsername"><br />
                            </div>
                            <div class="input">
                                <label for ="BasicAuthPassword"><?php echo __('Password');?></label>
                                <input class="form-control" type="text" id="BasicAuthPassword"><br />
                            </div>
                        </fieldset>
                        <span class="btn-inverse btn" onClick="add_basic_auth();" style="line-height:10px; padding: 4px 4px;"><?php echo __('Add basic auth header'); ?></span>
                </div>
            </div><br />
            </div>
            <div class="input clear"></div>
            <div id="DeleteLocalFileDiv" class="optionalField">
                    <?php
                        echo $this->Form->input('delete_local_file', array(
                                'label' => __('Remove input after ingestion')
                        ));
                ?>
            </div>
            <div class="input clear"></div>
            <?php
            echo $this->Form->input('url', array(
                    'div' => 'input clear',
                    'label' => __('URL'),
                    'placeholder' => __('URL of the feed'),
                    'class' => 'form-control span6'
            ));
            echo $this->Form->input('source_format', array(
                    'label' => __('Source Format'),
                    'div' => 'input clear',
                    'options' => $feed_types,
                    'class' => 'form-control span6'
            ));
        ?>
        <div id="OrgcDiv" class="optionalField">
        <?php
            echo $this->Form->input('orgc_id', array(
                    'label' => __('Creator organisation'),
                    'div' => 'input clear',
                    'options' => $orgs,
                    'class' => 'form-control span6'
            ));
        ?>
        </div>
        <div id="TargetDiv" class="optionalField">
        <?php
            echo $this->Form->input('fixed_event', array(
                    'label' => __('Target Event'),
                    'div' => 'input clear',
                    'options' => array('New Event Each Pull', 'Fixed Event'),
                    'class' => 'form-control span6'
            ));
        ?>
        </div>
        <div id="TargetEventDiv" class="optionalField">
        <?php
            echo $this->Form->input('target_event', array(
                    'label' => __('Target Event ID'),
                    'div' => 'input clear',
                    'placeholder' => __('Leave blank unless you want to reuse an existing event.'),
                    'class' => 'form-control span6'
            ));
        ?>
        </div>
        <div id="settingsCsvValueDiv" class="optionalField">
            <?php
                echo $this->Form->input('Feed.settings.csv.value', array(
                        'label' => __('Value field(s) in the CSV'),
                        'title' => __('Select one or several fields that should be parsed by the CSV parser and converted into MISP attributes'),
                        'div' => 'input clear',
                        'placeholder' => __('2,3,4 (column position separated by commas)'),
                        'class' => 'form-control span6'
                ));
            ?>
        </div>
        <div id="settingsCsvDelimiterDiv" class="optionalField">
            <?php
                echo $this->Form->input('Feed.settings.csv.delimiter', array(
                        'label' => __('Delimiter'),
                        'title' => __('Set the default CSV delimiter (default = ",")'),
                        'div' => 'input clear',
                        'placeholder' => ',',
                        'class' => 'form-control span6'
                ));
            ?>
        </div>
        <div id="settingsCommonExcluderegexDiv" class="optionalField">
            <?php
                echo $this->Form->input('Feed.settings.common.excluderegex', array(
                        'label' => __('Exclusion Regex'),
                        'title' => __('Add a regex pattern for detecting iocs that should be skipped (this can be useful to exclude any references to the actual report / feed for example)'),
                        'div' => 'input clear',
                        'placeholder' => __('Regex pattern, for example: "/^https://myfeedurl/i"'),
                        'class' => 'form-control span6'
                ));
            ?>
        </div>
        <div id="PublishDiv" class="input clear optionalField">
        <?php
            echo $this->Form->input('publish', array(
                    'label' => __('Auto Publish'),
                    'type' => 'checkbox',
                    'class' => 'form-control'
            ));
        ?>
        </div>
        <div id="OverrideIdsDiv" class="input clear optionalField">
        <?php
            echo $this->Form->input('override_ids', array(
                    'label' => __('Override IDS Flag'),
                    'title' => __('If checked, the IDS flags will always be set to off when pulling from this feed'),
                    'type' => 'checkbox',
                    'class' => 'form-control'
            ));
        ?>
        </div>
        <div id="DeltaMergeDiv" class="input clear optionalField">
        <?php
            echo $this->Form->input('delta_merge', array(
                    'label' => __('Delta Merge'),
                    'title' => __('Merge attributes (only add new attributes, remove revoked attributes)'),
                    'type' => 'checkbox',
                    'class' => 'form-control'
            ));
        ?>
        </div>
        <?php
            echo $this->Form->input('distribution', array(
                    'options' => array($distributionLevels),
                    'div' => 'input clear',
                    'label' => __('Distribution'),
            ));
        ?>
        <div id="SGContainer" style="display:none;">
        <?php
            if (!empty($sharingGroups)) {
                echo $this->Form->input('sharing_group_id', array(
                        'options' => array($sharingGroups),
                        'label' => __('Sharing Group'),
                ));
            }
        ?>
        </div>
        <div class="input clear"></div>
        <?php
            echo $this->Form->input('tag_id', array(
                    'options' => $tags,
                    'label' => __('Default Tag'),
            ));
        echo $this->Form->input('pull_rules', array('style' => 'display:none;', 'label' => false, 'div' => false));
    ?>
    </fieldset>
    <b><?php echo __('Filter rules');?>:</b><br />
    <span id="pull_tags_OR" style="display:none;"><?php echo __('Events with the following tags allowed');?>: <span id="pull_tags_OR_text" style="color:green;"></span><br /></span>
    <span id="pull_tags_NOT" style="display:none;"><?php echo __('Events with the following tags blocked');?>: <span id="pull_tags_NOT_text" style="color:red;"></span><br /></span>
    <span id="pull_orgs_OR" style="display:none;"><?php echo __('Events with the following organisations allowed');?>: <span id="pull_orgs_OR_text" style="color:green;"></span><br /></span>
    <span id="pull_orgs_NOT" style="display:none;"><?php echo __('Events with the following organisations blocked');?>: <span id="pull_orgs_NOT_text" style="color:red;"></span><br /></span>
    <span id="pull_url_params" style="display:none;"><?php echo __('Additional parameters: ');?><span id="pull_url_params_text" style="color:green;"></span><br /></span>
    <span id="pull_modify"  class="btn btn-inverse" style="line-height:10px; padding: 4px 4px;"><?php echo __('Modify');?></span><br /><br />
    <?php
    echo $this->Form->button(__('Edit'), array('class' => 'btn btn-primary'));
    echo $this->Form->end();
    ?>
    <div id="hiddenRuleForms">
        <?php
            $pullRules = json_decode($feed['Feed']['rules'], true);
            $pullRules['url_params'] = json_decode($pullRules['url_params'], true);
            $modalData = [
                'data' => [
                    'title' => __('Set PULL rules'),
                    'content' => [
                        [
                            'html' => $this->element('serverRuleElements/pull', [
                                'context' => 'feeds',
                                'allTags' => $tags,
                                'allOrganisations' => $orgs,
                                'ruleObject' => $pullRules
                            ])
                        ]
                    ],
                ],
                'type' => 'xl',
                'class' => 'pull-rule-modal',
                'confirm' => [
                    'title' => __('Update'),
                    'onclick' => "serverRulesUpdateState('pull');"
                ]
            ];
            echo $this->element('genericElements/infoModal', $modalData);
        ?>
    </div>
</div>
<?php
    echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'feeds', 'menuItem' => 'edit'));
?>
<script type="text/javascript">
//
var formInfoValues = {
        'ServerUrl' : "<?php echo __('The base-url to the external server you want to sync with. Example: https://misppriv.circl.lu');?>",
        'ServerName' : "<?php echo __('A name that will make it clear to your users what this instance is. For example: Organisation A\'s instance');?>",
        'ServerOrganization' : "<?php echo __('The organization having the external server you want to sync with. Example: BE');?>",
        'ServerAuthkey' : "<?php echo __('You can find the authentication key on your profile on the external server.');?>",
        'ServerPush' : "<?php echo __('Allow the upload of events and their attributes.');?>",
        'ServerPull' : "<?php echo __('Allow the download of events and their attributes from the server.');?>",
        'ServerUnpublishEvent' : '<?php echo __('Unpublish new event (working with Push event).');?>',
        'ServerPublishWithoutEmail' : '<?php echo __('Publish new event without email (working with Pull event).');?>',
        'ServerSubmittedCert' : "<?php echo __('You can also upload a certificate file if the instance you are trying to connect to has its own signing authority.');?>",
        'ServerSelfSigned' : "<?php echo __('Click this, if you would like to allow a connection despite the other instance using a self-signed certificate (not recommended).');?>"
};


var rules = {"pull": {"tags": {"OR":[], "NOT":[]}, "orgs": {"OR":[], "NOT":[]}}};
var validOptions = ['pull'];
var validFields = ['tags', 'orgs'];
var modelContext = 'Feed';
var tags = [];
var orgs = [];

$(document).ready(function() {
    rules = convertServerFilterRules(rules);
    feedDistributionChange();
    $("#pull_modify").click(function() {
        $('#genericModal.pull-rule-modal').modal().on('shown', function () {
            var $containers = $(this).find('.rules-widget-container')
            $containers.each(function() {
                var initFun = $(this).data('funname');
                if (typeof window[initFun] === 'function') {
                    window[initFun]()
                }
            })
            if (typeof window['cm'] === "object") {
                window['cm'].refresh()
            }
        });
    });
    $("#FeedDistribution").change(function() {
        feedDistributionChange();
    });
    $('#basicAuthFormEnable').click(function() {
        $('#basicAuthFormEnable').hide();
        $('#basicAuthForm').show();
    })
    feedFormUpdate();
});

$("#FeedSourceFormat, #FeedFixedEvent, #FeedInputSource").change(function() {
    feedFormUpdate();
});
</script>