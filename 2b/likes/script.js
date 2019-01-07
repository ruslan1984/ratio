 BX.ready(function(){	
 	
 	const likeBlock 	= document.querySelector('.likeBlock'); 	 	
 	const likeButton	= likeBlock.querySelector('.form-likes__button');
	const likeBlock__usersList 	= likeBlock.querySelector('.likeBlock__usersList');
	const formLikes 	= 	likeBlock.querySelector('.likeBlock__form-likes');			
	BX.bind(
		BX('formLikesBind'), 'submit', function(e){			
			var	liked 		=	Number(e.target.attributes.liked.value);				
			var userID 		= 	e.target.attributes.userID.value;
			var userLogin	= 	e.target.attributes.userLogin.value;
			var elementID 	=	e.target.attributes.elementID.value;						
		    BX.ajax({
		        url: e.target.attributes.action.value,
	           	data: {'userID': userID, 'elementID':elementID},
	            method: 'POST',
	            dataType: 'json',
	            timeout: 5,
	            async: true,
	            processData: true,
	            scriptsRunFirst: true,
	            emulateOnload: true,
	            start: true,
	            cache: false,
	            onsuccess: function(){	            	
	            	if(liked===0){
	            		e.target.attributes.liked.value=1;
	     				var firstElement=likeBlock__usersList.firstChild;
		           		var newUser = document.createElement('div');	            		
		            	newUser.textContent=userLogin;
		            	newUser.classList.add('likeBlock__user');
		            	newUser.setAttribute('userID',userID);
						likeBlock__usersList.insertBefore(newUser,firstElement);																	
						likeButton.classList.remove('likeBlock__likeFon');
						likeButton.textContent='Больше не нравится';						
	            	}else{	     
	            		var removeUser=likeBlock__usersList.querySelector('[userID="'+userID+'"]');	            	
	            		likeBlock__usersList.removeChild(removeUser);
						likeButton.classList.add('likeBlock__likeFon');	
	            		likeButton.textContent='Мне нравиться';
	            		e.target.attributes.liked.value=0;
	            	}
	            },
	            onfailure: function(){

	            }
	        });
	        BX.PreventDefault(e);
		});
    });