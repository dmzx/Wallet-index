function openQR(e){
	var src = $(e).find('img').attr('src');
	var title = $(e).find('img').attr('title');

	swal.fire({
		title: title,
		text: title + ' ' + walletIndex.lang.walletindexQRtext,
		imageUrl: src,
		imageAlt: title,
		animation: true,
		confirmButtonText: walletIndex.lang.walletindexQRclose,
		confirmButtonColor: '#82c545',
		content:true
	});
	e.preventDefault();
}