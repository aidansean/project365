from project_module import project_object, image_object, link_object, challenge_object

p = project_object('project365', 'Project 365')
p.domain = 'http://www.aidansean.com/'
p.path = '365'
p.preview_image    = image_object('%s/images/project.jpg'   %p.path, 150, 250)
p.preview_image_bw = image_object('%s/images/project_bw.jpg'%p.path, 150, 250)
p.folder_name = 'aidansean'
p.github_repo_name = 'project365'
p.mathjax = False
p.links.append(link_object(p.domain, '365/', 'Live page'))
p.introduction = 'I keep a project 365 page, which means one photo per day.  It\'s a good way to keep track of the passage of time and as I take my camera everywhere with me it\' quite easy.  After using a blog for the photos I soon became frustrated at how long it took, so I decided to make my own wrapper for the images.'
p.overview = '''The images are stored on disk, with automator scripts to resize them to the appropriate sizes.  They are then arranged hierarchically according to date so that they can be arranged easily on the page.  A list of comments, also arranged by date, is read via PHP and served up to the client as a Javascript file.  There are event listeners added to each thumbnail so that the user can browse the images.'''

p.challenges.append(challenge_object('Given the large number of images there are a huge amount of HTTP requests.', 'Each thumbnail image comes with an overhead that could be reduced in the following way.  The thumbnails can be combined into a single image which is indexed according to the date of each thumbnail.  This can then be sliced up by client using a canvas and arranged on the page.  Doing this would save a lot of time and bandwidth for both the client and server, but would require rewriting much of the code, and it is a low priority.', 'To be revisited.'))
