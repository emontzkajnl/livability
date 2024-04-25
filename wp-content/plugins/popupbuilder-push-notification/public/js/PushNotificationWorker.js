self.addEventListener('push', function(event) {
    var notificationObject = JSON.parse(event.data.text());

    var title = notificationObject.title;
    var options = {
        body: notificationObject.msg,
        icon: notificationObject.icon,
        badge: notificationObject.badge
    };
    self.notificationURL = notificationObject.url;
    self.nonce = notificationObject.nonce;
    self.ajaxUrl = notificationObject.ajaxUrl;
    self.campaignId = notificationObject.campaignId;
    var data = {
        action: 'sgpb_notification_delivered',
        nonce: self.nonce,
        campaignId: self.campaignId
    };

    var str = "";
    for (var index in data) {
        if (str != "") {
            str += "&";
        }
        str += index + "=" + encodeURIComponent(data[index]);
    }
    fetch(self.ajaxUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
        },
        body: str,
        credentials: 'same-origin'
    });

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    var data = {
        action: 'sgpb_notification_click',
        nonce: self.nonce,
        campaignId: self.campaignId
    };

    var str = "";
    for (var index in data) {
        if (str != "") {
            str += "&";
        }
        str += index + "=" + encodeURIComponent(data[index]);
    }
    fetch(self.ajaxUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
        },
        body: str,
        credentials: 'same-origin'
    });

    event.waitUntil(
        clients.openWindow(self.notificationURL)
    );
});