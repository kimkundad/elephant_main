$(document).ready(function () {
    var part = $("#part").attr("href");

    //TODO: ROOMS
    $('#room').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/room/gallery/img-1.jpg',
                'thumb': part+'assets/images/room/gallery/img-1.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-2.jpg',
                'thumb': part+'assets/images/room/gallery/img-2.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-3.jpg',
                'thumb': part+'assets/images/room/gallery/img-3.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-4.jpg',
                'thumb': part+'assets/images/room/gallery/img-4.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-5.jpg',
                'thumb': part+'assets/images/room/gallery/img-5.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-6.jpg',
                'thumb': part+'assets/images/room/gallery/img-6.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-7.jpg',
                'thumb': part+'assets/images/room/gallery/img-7.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-8.jpg',
                'thumb': part+'assets/images/room/gallery/img-8.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-9.jpg',
                'thumb': part+'assets/images/room/gallery/img-9.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-10.jpg',
                'thumb': part+'assets/images/room/gallery/img-10.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-11.jpg',
                'thumb': part+'assets/images/room/gallery/img-11.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-12.jpg',
                'thumb': part+'assets/images/room/gallery/img-12.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-13.jpg',
                'thumb': part+'assets/images/room/gallery/img-13.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-14.jpg',
                'thumb': part+'assets/images/room/gallery/img-14.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            },{
                'src': part+'assets/images/room/gallery/img-15.jpg',
                'thumb': part+'assets/images/room/gallery/img-15.jpg',
                'subHtml': '<h4>Accommodations</h4>'
            }]
        })
    });
    $('#superior').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/room/superior_room/1.jpg',
                'thumb': part+'assets/images/room/superior_room/1.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/2.jpg',
                'thumb': part+'assets/images/room/superior_room/2.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/3.jpg',
                'thumb': part+'assets/images/room/superior_room/3.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/4.jpg',
                'thumb': part+'assets/images/room/superior_room/4.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/5.jpg',
                'thumb': part+'assets/images/room/superior_room/5.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/6.jpg',
                'thumb': part+'assets/images/room/superior_room/6.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/7.jpg',
                'thumb': part+'assets/images/room/superior_room/7.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/8.jpg',
                'thumb': part+'assets/images/room/superior_room/8.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/9.jpg',
                'thumb': part+'assets/images/room/superior_room/9.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/10.jpg',
                'thumb': part+'assets/images/room/superior_room/10.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/11.jpg',
                'thumb': part+'assets/images/room/superior_room/11.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/12.jpg',
                'thumb': part+'assets/images/room/superior_room/12.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            },{
                'src': part+'assets/images/room/superior_room/13.jpg',
                'thumb': part+'assets/images/room/superior_room/13.jpg',
                'subHtml': '<h4>Superior Room</h4>'
            }]
        })
    });
    $('#deluxe').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/room/deluxe_room/1.jpg',
                'thumb': part+'assets/images/room/deluxe_room/1.jpg',
                'subHtml': '<h4>Deluxe Room</h4>'
            },{
                'src': part+'assets/images/room/deluxe_room/2.jpg',
                'thumb': part+'assets/images/room/deluxe_room/2.jpg',
                'subHtml': '<h4>Deluxe Room</h4>'
            },{
                'src': part+'assets/images/room/deluxe_room/3.jpg',
                'thumb': part+'assets/images/room/deluxe_room/3.jpg',
                'subHtml': '<h4>Deluxe Room</h4>'
            },{
                'src': part+'assets/images/room/deluxe_room/4.jpg',
                'thumb': part+'assets/images/room/deluxe_room/4.jpg',
                'subHtml': '<h4>Deluxe Room</h4>'
            },{
                'src': part+'assets/images/room/deluxe_room/5.jpg',
                'thumb': part+'assets/images/room/deluxe_room/5.jpg',
                'subHtml': '<h4>Deluxe Room</h4>'
            },{
                'src': part+'assets/images/room/deluxe_room/6.jpg',
                'thumb': part+'assets/images/room/deluxe_room/6.jpg',
                'subHtml': '<h4>Deluxe Room</h4>'
            }]
        })
    });
    $('#superior_seaview_bathtub').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/room/superior_seaview_bathtub/1.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/1.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/2.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/2.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/3.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/3.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/4.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/4.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/5.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/5.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/6.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/6.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/7.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/7.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/8.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/8.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/9.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/9.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/10.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/10.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/11.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/11.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/superior_seaview_bathtub/12.jpg',
                'thumb': part+'assets/images/room/superior_seaview_bathtub/12.jpg',
                'subHtml': '<h4>Superior Seaview with Bathtub</h4>'
            }]
        })
    });
    $('#wood_house_seaview').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/room/wood_house_seaview/1.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/1.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/2.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/2.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/3.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/3.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/4.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/4.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/5.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/5.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/6.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/6.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/7.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/7.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/8.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/8.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/9.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/9.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/10.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/10.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/11.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/11.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/12.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/12.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/13.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/13.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/14.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/14.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/15.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/15.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/16.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/16.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/17.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/17.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            },{
                'src': part+'assets/images/room/wood_house_seaview/18.jpg',
                'thumb': part+'assets/images/room/wood_house_seaview/18.jpg',
                'subHtml': '<h4>Wood House Seaview with Bathtub</h4>'
            }]
        })
    });
    $('#seaview_villa').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/room/sea_view_villa/1.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/1.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/2.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/2.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/3.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/3.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/4.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/4.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/5.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/5.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/6.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/6.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/7.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/7.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/8.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/8.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/9.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/9.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/10.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/10.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/11.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/11.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/12.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/12.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            },{
                'src': part+'assets/images/room/sea_view_villa/13.jpg',
                'thumb': part+'assets/images/room/sea_view_villa/13.jpg',
                'subHtml': '<h4>Sea View Villa</h4>'
            }]
        })
    });
    $('#luxuryVilla_seaview').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/room/luxury_villa_seaview/12.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/12.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/1.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/1.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/2.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/2.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/3.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/3.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/4.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/4.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/5.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/5.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/6.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/6.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/7.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/7.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/8.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/8.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/9.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/9.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/10.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/10.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/11.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/11.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/13.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/13.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/14.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/14.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/15.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/15.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/16.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/16.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/17.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/17.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/18.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/18.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            },{
                'src': part+'assets/images/room/luxury_villa_seaview/19.jpg',
                'thumb': part+'assets/images/room/luxury_villa_seaview/19.jpg',
                'subHtml': '<h4>Luxury Villa Seaview With Bathtub & Jacuzzi</h4>'
            }]
        })
    });


    //TODO: EXPERIENCE
    $('#pool').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/experience/pool/img-5.jpg',
                'thumb': part+'assets/images/experience/pool/img-5.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-1.jpg',
                'thumb': part+'assets/images/experience/pool/img-1.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-2.jpg',
                'thumb': part+'assets/images/experience/pool/img-2.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-3.jpg',
                'thumb': part+'assets/images/experience/pool/img-3.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-4.jpg',
                'thumb': part+'assets/images/experience/pool/img-4.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-6.jpg',
                'thumb': part+'assets/images/experience/pool/img-6.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-7.jpg',
                'thumb': part+'assets/images/experience/pool/img-7.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-8.jpg',
                'thumb': part+'assets/images/experience/pool/img-8.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-9.jpg',
                'thumb': part+'assets/images/experience/pool/img-9.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-10.jpg',
                'thumb': part+'assets/images/experience/pool/img-10.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-11.jpg',
                'thumb': part+'assets/images/experience/pool/img-11.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-12.jpg',
                'thumb': part+'assets/images/experience/pool/img-12.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-13.jpg',
                'thumb': part+'assets/images/experience/pool/img-13.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-14.jpg',
                'thumb': part+'assets/images/experience/pool/img-14.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-15.jpg',
                'thumb': part+'assets/images/experience/pool/img-15.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-16.jpg',
                'thumb': part+'assets/images/experience/pool/img-16.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-17.jpg',
                'thumb': part+'assets/images/experience/pool/img-17.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-18.jpg',
                'thumb': part+'assets/images/experience/pool/img-18.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            },{
                'src': part+'assets/images/experience/pool/img-19.jpg',
                'thumb': part+'assets/images/experience/pool/img-19.jpg',
                'subHtml': '<h4>Swimming Pool</h4>'
            }]
        })
    });
    $('#cafe').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/experience/cafe/img-1.jpg',
                'thumb': part+'assets/images/experience/cafe/img-1.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-2.jpg',
                'thumb': part+'assets/images/experience/cafe/img-2.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-3.jpg',
                'thumb': part+'assets/images/experience/cafe/img-3.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-4.jpg',
                'thumb': part+'assets/images/experience/cafe/img-4.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-5.jpg',
                'thumb': part+'assets/images/experience/cafe/img-5.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-6.jpg',
                'thumb': part+'assets/images/experience/cafe/img-6.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-7.jpg',
                'thumb': part+'assets/images/experience/cafe/img-7.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-8.jpg',
                'thumb': part+'assets/images/experience/cafe/img-8.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-9.jpg',
                'thumb': part+'assets/images/experience/cafe/img-9.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-10.jpg',
                'thumb': part+'assets/images/experience/cafe/img-10.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-11.jpg',
                'thumb': part+'assets/images/experience/cafe/img-11.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-12.jpg',
                'thumb': part+'assets/images/experience/cafe/img-12.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-13.jpg',
                'thumb': part+'assets/images/experience/cafe/img-13.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-14.jpg',
                'thumb': part+'assets/images/experience/cafe/img-14.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-15.jpg',
                'thumb': part+'assets/images/experience/cafe/img-15.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-16.jpg',
                'thumb': part+'assets/images/experience/cafe/img-16.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-17.jpg',
                'thumb': part+'assets/images/experience/cafe/img-17.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            },{
                'src': part+'assets/images/experience/cafe/img-18.jpg',
                'thumb': part+'assets/images/experience/cafe/img-18.jpg',
                'subHtml': '<h4>Cafe Bay View</h4>'
            }]
        })
    });
    $('#breakfast').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/experience/breakfast/img-1.jpg',
                'thumb': part+'assets/images/experience/breakfast/img-1.jpg',
                'subHtml': '<h4>Breakfast</h4>'
            },{
                'src': part+'assets/images/experience/breakfast/img-2.jpg',
                'thumb': part+'assets/images/experience/breakfast/img-2.jpg',
                'subHtml': '<h4>Breakfast</h4>'
            },{
                'src': part+'assets/images/experience/breakfast/img-3.jpg',
                'thumb': part+'assets/images/experience/breakfast/img-3.jpg',
                'subHtml': '<h4>Breakfast</h4>'
            },{
                'src': part+'assets/images/experience/breakfast/img-4.jpg',
                'thumb': part+'assets/images/experience/breakfast/img-4.jpg',
                'subHtml': '<h4>Breakfast</h4>'
            },{
                'src': part+'assets/images/experience/breakfast/img-5.jpg',
                'thumb': part+'assets/images/experience/breakfast/img-5.jpg',
                'subHtml': '<h4>Breakfast</h4>'
            }]
        })
    });
    $('#lobby').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/experience/lobby/img-1.jpg',
                'thumb': part+'assets/images/experience/lobby/img-1.jpg',
                'subHtml': '<h4>Lobby</h4>'
            },{
                'src': part+'assets/images/experience/lobby/img-2.jpg',
                'thumb': part+'assets/images/experience/lobby/img-2.jpg',
                'subHtml': '<h4>Lobby</h4>'
            },{
                'src': part+'assets/images/experience/lobby/img-3.jpg',
                'thumb': part+'assets/images/experience/lobby/img-3.jpg',
                'subHtml': '<h4>Lobby</h4>'
            },{
                'src': part+'assets/images/experience/lobby/img-4.jpg',
                'thumb': part+'assets/images/experience/lobby/img-4.jpg',
                'subHtml': '<h4>Lobby</h4>'
            },{
                'src': part+'assets/images/experience/lobby/img-5.jpg',
                'thumb': part+'assets/images/experience/lobby/img-5.jpg',
                'subHtml': '<h4>Lobby</h4>'
            },{
                'src': part+'assets/images/experience/lobby/img-6.jpg',
                'thumb': part+'assets/images/experience/lobby/img-6.jpg',
                'subHtml': '<h4>Lobby</h4>'
            }]
        })
    });
    $('#trip_ao_phangnga').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-0.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-0.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-1.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-1.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-2.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-2.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-3.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-3.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-4.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-4.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-5.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-5.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-6.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-6.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-7.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-7.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-8.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-8.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-9.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-9.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-10.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-10.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-11.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-11.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-12.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-12.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-13.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-13.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-14.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-14.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-15.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-15.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-16.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-16.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-17.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-17.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-18.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-18.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-19.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-19.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-20.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-20.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-21.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-21.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-22.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-22.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            },{
                'src': part+'assets/images/experience/trip_ao_phangnga/img-23.jpg',
                'thumb': part+'assets/images/experience/trip_ao_phangnga/img-23.jpg',
                'subHtml': '<h4>Trip Ao Phang Nga</h4>'
            }]
        })
    });
    $('#ban_hinrom').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/experience/ban_hinrom/img-2.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-2.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-1.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-1.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-3.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-3.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-4.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-4.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-5.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-5.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-6.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-6.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-7.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-7.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-8.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-8.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-9.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-9.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-10.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-10.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-11.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-11.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-12.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-12.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-13.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-13.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-14.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-14.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-15.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-15.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            },{
                'src': part+'assets/images/experience/ban_hinrom/img-16.jpg',
                'thumb': part+'assets/images/experience/ban_hinrom/img-16.jpg',
                'subHtml': '<h4>Ban Hin Rom</h4>'
            }]
        })
    });

    //TODO: DINING
    $('#restaurant').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/dining/restaurant/img-3.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-3.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-1.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-1.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-2.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-2.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-4.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-4.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-5.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-5.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-6.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-6.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-7.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-7.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-8.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-8.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-9.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-9.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-10.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-10.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-11.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-11.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-12.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-12.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-13.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-13.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-14.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-14.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-15.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-15.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-16.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-16.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-17.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-17.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            },{
                'src': part+'assets/images/dining/restaurant/img-18.jpg',
                'thumb': part+'assets/images/dining/restaurant/img-18.jpg',
                'subHtml': '<h4>Restaurant</h4>'
            }]
        })
    });
    $('#private_dinner').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/dining/private_dinner/img-4.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-4.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-1.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-1.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-2.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-2.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-3.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-3.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-5.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-5.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-6.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-6.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-7.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-7.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-8.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-8.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-9.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-9.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-10.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-10.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-11.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-11.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            },{
                'src': part+'assets/images/dining/private_dinner/img-12.jpg',
                'thumb': part+'assets/images/dining/private_dinner/img-12.jpg',
                'subHtml': '<h4>Private Dinner</h4>'
            }]
        })
    });

    $('#celebration').on('click', function () {
        $(this).lightGallery({
            dynamic: true,
            download: true,
            dynamicEl: [{
                'src': part+'assets/images/celebration/img-3.jpg',
                'thumb': part+'assets/images/celebration/img-3.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-1.jpg',
                'thumb': part+'assets/images/celebration/img-1.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-2.jpg',
                'thumb': part+'assets/images/celebration/img-2.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-4.jpg',
                'thumb': part+'assets/images/celebration/img-4.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-5.jpg',
                'thumb': part+'assets/images/celebration/img-5.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-6.jpg',
                'thumb': part+'assets/images/celebration/img-6.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-7.jpg',
                'thumb': part+'assets/images/celebration/img-7.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-8.jpg',
                'thumb': part+'assets/images/celebration/img-8.jpg',
                'subHtml': '<h4>Celebration</h4>'
            },{
                'src': part+'assets/images/celebration/img-9.jpg',
                'thumb': part+'assets/images/celebration/img-9.jpg',
                'subHtml': '<h4>Celebration</h4>'
            }]
        })
    });
});