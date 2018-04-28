// # A Freeboard Plugin that uses the Eclipse Paho javascript client to read MQTT messages

(function()
{
    //  Datasource Definition
    // -------------------
    freeboard.loadDatasourcePlugin({
        "type_name"   : "PubNub",
        "description":"PubNub is a realtime messaging network. See <a href=\"http://www.pubnub.com\">http://www.pubnub.com</a> for more details.",
        "display_name":"PubNub",
        "external_scripts":
            ["https://cdn.pubnub.com/pubnub.min.js"],
        "settings":[
            {
                "type": "text", "display_name": "Subscribe Key", "name": "subscribe_key"
            }, {
                "type": "text", "display_name": "Channel", "name": "channel"
            }
        ],
        // **newInstance(settings, newInstanceCallback, updateCallback)** (required) : A function that will be called when a new instance of this plugin is requested.
        // * **settings** : A javascript object with the initial settings set by the user. The names of the properties in the object will correspond to the setting names defined above.
        // * **newInstanceCallback** : A callback function that you'll call when the new instance of the plugin is ready. This function expects a single argument, which is the new instance of your plugin object.
        // * **updateCallback** : A callback function that you'll call if and when your datasource has an update for freeboard to recalculate. This function expects a single parameter which is a javascript object with the new, updated data. You should hold on to this reference and call it when needed.
        newInstance   : function(settings, newInstanceCallback, updateCallback)
        {
            newInstanceCallback(new pubnubDatasourcePlugin(settings, updateCallback));
        }
    });

    var pubnubDatasourcePlugin = function(d, e) {
        var b = this;
        var a = {};
        var c;
        this.onPubNubHistory = function(h) {
            var g = h[0];
            for (var f in g) {
                b.onPubNubMessage(g[f])
            }
        };
        this.onPubNubMessage = function(g) {
            if (_.isString(g)) {
                try {
                    var f = JSON.parse(g);
                    if (f) {
                        e(f)
                    }
                } catch (h) {}
            } else {
                e(g)
            }
        };
        this.changeChannel = function(f) {
            try {
                c.unsubscribe({
                    channel: a.channel
                })
            } catch (g) {}
            if (f) {
                c.subscribe({
                    channel: f,
                    message: b.onPubNubMessage
                })
            }
        };
        this.closeConnection = function() {
            b.changeChannel(null);
            c = null
        };
        this.updateNow = function() {
            c.history({
                channel: a.channel,
                callback: b.onPubNubHistory
            })
        };
        this.onDispose = function() {
            b.closeConnection()
        };
        this.onSettingsChanged = function(f) {
            if (f.subscribe_key !== a.subscribe_key) {
                c = PUBNUB.init({
                    subscribe_key: f.subscribe_key,
                    ssl: true
                })
            }
            if (f.channel !== a.channel) {
                b.changeChannel(f.channel)
            }
            a = f
        };
        b.onSettingsChanged(d)
    };
}());