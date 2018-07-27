(function (global, factory) {
    "use strict";

    if (typeof module === "object" && typeof module.exports === "object") {
        module.exports = factory(global, true);
    } else {
        factory(global);
    }

// Pass this if window is not defined yet
})(typeof window !== "undefined" ? window : this, function (window, noGlobal) {
    const scriptTag = document.querySelector('script[data-web-push-client]');

    if (null === scriptTag) {
        console.log('Do not forget to add "data-webpushclient", i.e. <script src="web-push/client.js" data-web-push-client></script>');
        throw Error("Cannot find where web-push/client.js is.");
    }

    const WebPushClient = function (options) {
        return {
            options: {},
            worker: null,
            registration: null,
            subscription: null,

            init: function init(options) {
                this.options = options || {};

                if (!options.url) {
                    throw Error('Url has not been defined.');
                }

                this.options.url = options.url;
                this.options.serviceWorkerPath = this.options.serviceWorkerPath || scriptTag.src.replace('/client.js', '/worker.js?' + Date.now());
                this.options.promptIfNotSubscribed = 'boolean' === typeof options.promptIfNotSubscribed ? options.promptIfNotSubscribed : true;

                return this.initServiceWorker();
            },

            initServiceWorker: function () {
                const that = this;

                if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                    return;
                }

                that.registerServiceWorker(this.options.serviceWorkerPath).then(function (registration) {
                    that.registration = registration;
                    that.getSubscription(registration).then(function (subscription) {
                        // If a subscription was found, return it.
                        if (subscription) {
                            that.subscription = subscription;
                            return subscription;
                        } else {
                            if (true === that.options.promptIfNotSubscribed) {
                                return that.subscribe();
                            }
                        }
                    });
                });

                return this;
            },

            askPermission: function () {
                return new Promise(function (resolve, reject) {
                    const permissionResult = Notification.requestPermission(function (result) {
                        resolve(result);
                    });

                    if (permissionResult) {
                        permissionResult.then(resolve, reject);
                    }
                }).then(function (permissionResult) {
                    if (permissionResult !== 'granted') {
                        throw new Error('Permission was not granted.');
                    }
                });
            },

            getNotificationPermissionState: function () {
                if (navigator.permissions) {
                    return navigator.permissions.query({ name: 'notifications' }).then(function (result) {
                        return result.state;
                    });
                }

                return new Promise(function (resolve) {
                    resolve(Notification.permission);
                });
            },

            subscribe: function () {
                const that = this;
                return that.registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: that.encodeServerKey(that.options.serverKey)
                }).then(function (subscription) {
                    that.subscription = subscription;
                    return that.registerSubscription(subscription).then(function (subscription) {
                        if ('function' === typeof that.options.onSubscribe) {
                            return that.options.onSubscribe(subscription);
                        }
                    });
                });
            },

            unsubscribe: function () {
                const that = this;
                return this.getSubscription(this.registration).then(function (subscription) {
                    that.unregisterSubscription(subscription);
                    if ('function' === typeof that.options.onUnsubscribe) {
                        return that.options.onUnsubscribe(subscription);
                    }
                });
            },

            revoke: function () {
                const that = this;
                return this.getSubscription(this.registration).then(function (subscription) {
                    subscription.unsubscribe().then(function () {
                        that.unregisterSubscription(subscription);
                        if ('function' === typeof that.options.onUnsubscribe) {
                            return that.options.onUnsubscribe(subscription);
                        }
                    });
                });
            },

            registerServiceWorker: function (serviceWorkerPath) {
                const that = this;
                return navigator.serviceWorker.register(serviceWorkerPath).then(function (registration) {
                    that.worker = registration.active || registration.installing;
                    return registration;
                });
            },

            getSubscription: function (registration) {
                return registration.pushManager.getSubscription();
            },

            registerSubscription: function (subscription) {
                const that = this;
                const meta = subscription.toJSON();
                const data = {
                    'platform': 'web',
                    'token': meta.keys.auth,
                    'metas': meta,
                };

                return fetch(this.options.url, {
                    method: 'POST',
                    mode: 'cors',
                    credentials: 'include',
                    cache: 'default',
                    headers: new Headers({
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify(data)
                }).then(function (response) {
                    // auto re subscribe
                    if (400 <= response.status) {
                        subscription.unsubscribe();
                    }

                    return subscription;
                });
            },

            unregisterSubscription: function (subscription) {
                const meta = subscription.toJSON();
                return fetch(this.options.url.replace(/\/$/, '') + '/' + meta.keys.auth, {
                    method: 'DELETE',
                    mode: 'cors',
                    credentials: 'include',
                    cache: 'default',
                    headers: new Headers({
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }),
                });
            },

            encodeServerKey: function encodeServerKey(serverKey) {
                const padding = '='.repeat((4 - serverKey.length % 4) % 4);
                const base64 = (serverKey + padding).replace(/\-/g, '+').replace(/_/g, '/');

                const rawData = window.atob(base64);
                const outputArray = new Uint8Array(rawData.length);

                for (let i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }
                return outputArray;
            }

        }.init(options);
    };

    try {
        new WebPushClient(JSON.parse(scriptTag.innerHTML));
    } catch (e) {
    }

    if (!noGlobal) {
        window.WebPushClient = WebPushClient;
    }

    return WebPushClient;
});
