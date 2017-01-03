/**
 * RumbleTalk SDK v0.31
 *
 * Site: https://www.rumbletalk.com
 * SDK support: https://www.rumbletalk.com/support/API_Auto_Login/
 */

/*jslint browser: true */
/*global window */

(function (window) {
    'use strict';
    
    var RT = {
        baseURL: 'https://connect.rumbletalk.net/login.php?',

        /* use postMessage for Safari (no support for subdomain cookies) */
        usePostMessage: navigator.userAgent.indexOf('Safari') > -1 &&
            navigator.userAgent.indexOf('Macintosh') > -1,
        
        initiate: function () {
            var mqHandle = window[window.rumbleTalkMessageQueueName].q;
            
            /* handle messages in queue, if there are any */
            if (mqHandle) {
                while (mqHandle.length > 0) {
                    RT.handleMq(mqHandle.shift());
                }
            }
            
            /* update the global function to handle the messages */
            window[window.rumbleTalkMessageQueueName] = RT.handleMq;
        },
        
        handleMq: function (message) {
            if (RT.usePostMessage && (!window.RumbleTalkChat[message.data.hash] ||
                !window.RumbleTalkChat[message.data.hash].iframe)) {
                setTimeout(
                    function () {
                        RT.handleMq(message);
                    },
                    100
                );
                
                return;
            }
            
            if (!RT.validateHash(message.data.hash)) {
                throw new Error('Invalid chat hash');
            }
            
            switch (message.type) {
                case 'login':
                    RT.login(message.data);
                    break;
                    
                case 'logout':
                    RT.logout(message.data);
                    break;
            }
        },

        login: function (data) {
            var sendData = 'login:',
                query = 'h=' + data.hash;

            /* handle username value */
            data.username = RT.trim(data.username);
            if (!RT.validateUsername(data.username)) {
                throw new Error('invalid username');
            }
            sendData += data.username;
            query += '&u=' + encodeURIComponent(data.username);

            /* handle, if set, password value */
            if (data.password) {
                if (!RT.validatePassword(data.password)) {
                    throw new Error('invalid password');
                }
                sendData += ',' + data.password;
                query += '&p=' + encodeURIComponent(data.password);
            }

            /* for non-mac users, login with cookies */
            if (!RT.usePostMessage) {
                var connectImage = new Image();

                connectImage.src = RT.baseURL + query;

                return;
            }

            /* for mac users, use post message */
            var loginInterval = setInterval(
                function () {
                    try {
                        var target = window.RumbleTalkChat[data.hash].iframe.contentWindow ||
                                window.RumbleTalkChat[data.hash].iframe;
                        target.postMessage(
                            sendData,
                            window.RumbleTalkChat[data.hash].protocol + window.RumbleTalkChat[data.hash].server
                        );
                    } catch (ignore) {}
                },
                1000
            );
            
            window.addEventListener(
                'message',
                function handlePostMessage(event) {
                    /* validates the origin to be from a chat */
                    if (!RT.validateChatOrigin(event.origin)) {
                        return;
                    }
                    
                    /* validate that the message is of a successful login of the specific chat */
                    // if (event.data === 'RTloginSuccess:' + data.hash) {
                    if (event.data.indexOf('RTloginSuccess') === 0) {
                        clearInterval(loginInterval);
                        window.removeEventListener('message', handlePostMessage);
                    }
                },
                false
            );
        },

        trim: function (str) {
            return str.replace(/^\s+|\s+$/g, '');
        },

        validateUsername: function (username) {
            return /^[^,]*[^,0-9]+[^,]*$/.test(username) && username.length < 31;
        },

        validatePassword: function (password) {
            return /[^,]+/.test(password);
        },
        
        validateHash: function (hash) {
            return hash.length == 8;
        },
        
        validateChatOrigin: function (origin) {
            return (/^https?:\/\/service[0-9]{1,2}.rumbletalk.net(\:4433)?$/).test(origin);
        }
    };
    
    RT.initiate();

}(window));