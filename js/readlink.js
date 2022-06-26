$(document).ready
(
	function()
	{
		var actionsReadLink =
		{
			init: function()
			{
				OCA.Files.fileActions.registerAction
				(
					{
						/*
						type: OCA.Files.FileActions.TYPE_INLINE,
						displayName: '',
						*/

						type: OCA.Files.FileActions.TYPE_DROPDOWN,
						displayName: t('readlink', 'Display full path'),

						mime: 'all',
						name: 'readlink',
						iconClass: 'icon-link',
						permissions: OC.PERMISSION_READ,
						actionHandler: function(filename, context)
						{
							// console.log('READLINK request: ' + filename + ' (' + context.fileInfoModel.attributes.id + ')');

							$.ajax
							(
								{
									type: 'post',
									url: OC.filePath('readlink', 'ajax', 'readlink.php'),
									data: { fileId: context.fileInfoModel.attributes.id },
									success: function(element)
									{
										// console.log('READLINK response: ' + element);

										response = JSON.parse(element);

										if(typeof response.path == 'string' && typeof response.local == 'boolean')
										{
											prompt((response.local ? t('readlink', 'Local path:') : t('readlink', 'External URL:')) + '', response.path);
										}
										else
										{
											alert(t('readlink', 'Could not get full path!'));
										}
									}
								}
							);
						}
					}
				);
			},
		}

		actionsReadLink.init();
	}
);
