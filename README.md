# NinjaVan Tracking API
- Return JSON formatted string of NinjaVan Tracking details
- Can be use for tracking the NinjaVan parcel in your own project/system
- Note:
  - This is not the official API, this is actually just a "hack", or workaround for obtaining the tracking data.
  - This API will fetch data directly from the NinjaVan Tracking website, so if there are any problem with the site, this API will also affected.

# Installation
```composer require afzafri/ninjavan-tracking-api```

# Usage
- ```http://site.com/api.php?trackingNo=CODE```
- where ```CODE``` is your parcel tracking number
- It will then return a JSON formatted string, you can parse the JSON string and do what you want with it.

# Sample Response
```
{
   "http_code":200,
   "error_msg":"No error",
   "message":"Record Found",
   "data":[
      {
         "date_time": "2025-05-28T02:15:14Z",
         "process": "Order created",
         "location": ""
      },
      {
         "date_time": "2025-05-28T08:55:56Z",
         "process": "Parcel dropped off at Parcel Dropoff Counter / Box",
         "location": "ASNP - Ninja Van at Kota Permai"
      },
      {
         "date_time": "2025-05-28T09:10:17Z",
         "process": "Successfully picked up from sender",
         "location": "PUDO Penang"
      },
      {
         "date_time": "2025-05-28T09:10:46Z",
         "process": "Departed Parcel Dropoff Counter / Box",
         "location": "ASNP - Ninja Van at Kota Permai"
      },
      {
         "date_time": "2025-05-28T12:06:46Z",
         "process": "Parcel is being processed at Ninja Van warehouse",
         "location": "Sort Centre Juru"
      },
      {
         "date_time": "2025-05-28T12:36:08Z",
         "process": "Departed Ninja Van warehouse",
         "location": "In Transit"
      },
      {
         "date_time": "2025-05-29T16:57:27Z",
         "process": "Parcel is being processed at Ninja Van warehouse",
         "location": "Sort Centre Shah Alam"
      },
      {
         "date_time": "2025-05-30T12:43:55Z",
         "process": "Departed Ninja Van warehouse",
         "location": "In Transit"
      },
      {
         "date_time": "2025-06-03T02:21:05Z",
         "process": "Parcel is being processed at Ninja Van warehouse",
         "location": "Station Pasir Puteh"
      },
      {
         "date_time": "2025-06-03T03:30:18Z",
         "process": "Parcel is being delivered",
         "location": "Station Pasir Puteh"
      },
      {
         "date_time": "2025-06-03T06:38:37Z",
         "process": "Successfully delivered",
         "location": ""
      }
   ],
   "info":{
      "creator":"Afif Zafri (afzafri)",
      "project_page":"https:\/\/github.com\/afzafri\/NinjaVan-Tracking-API",
      "date_updated":"02\/07\/2025"
   }
}
```

# Created By
- Afif Zafri
- Date: 02/07/2025
- Contact: http://fb.me/afzafri

# License
This library is under ```MIT license```, please look at the LICENSE file
