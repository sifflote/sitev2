<?php


namespace App\Controller\Forum;

use App\Controller\Helper\Paginator\PaginatorInterface;
use App\Entity\Forum\Tag;
use App\Entity\Forum\Topic;
use App\Form\Forum\ForumTopicForm;
use App\Repository\Forum\TagRepository;
use App\Repository\Forum\TopicRepository;
use Exception;
use App\Controller\Forum\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    private TagRepository $tagRepository;
    private TopicRepository $topicRepository;
    private PaginatorInterface $paginator;

    public function __construct(TagRepository $tagRepository, TopicRepository $topicRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->topicRepository = $topicRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param TagRepository $tagRepository
     * @return Response
     * @Route("/forum", name="forum")
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->tag(null);

    }

    /**
     * @Route("/forum/new", name="forum_new")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request,TopicService $service): Response
    {
        $topic = (new Topic())->setContent($this->renderView('forum/template/placeholder.text.twig'));
        $form = $this->createForm(ForumTopicForm::class, $topic);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->createTopic($topic);
            $this->addFlash('success', 'Le sujet a bien été créé');
            return $this->redirectToRoute('forum');
        }
        return $this->render('forum/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/forum/{slug<[a-z0-9\-]+>}-{id<\d+>}", name="forum_tag")
     * @param Tag|null $tag
     * @return Response
     */
    public function tag(?Tag $tag): Response
    {
        $topics = $this->paginator->paginate($this->topicRepository->queryAllForTag($tag));
        //$topics = $this->topicRepository->queryAllForTag($tag);
        $parent = -1;
        $tag_title = null;
        $header_color1 = null;
        $header_color2 = null;
        if($tag) {
            if ($tag->getParent() !== null) {
                $parent = $tag->getParent()->getId();
                $tag_title = $tag->getParent()->getName() . ' - ' . $tag->getName();
            }else{
                $tag_title = $tag->getName();
            }
            $header_color1 = $tag->getColor();
            $header_color2 = $tag->getColor2();
        }

        return $this->render('forum/index.html.twig', [
            'tags' => $this->tagRepository->findTree(),
            'topics' => $topics,
            'menu' => 'forum',
            'parent' => $parent,
            'page_title' => $tag_title,
            'header_color1' => $header_color1,
            'header_color2' => $header_color2,
            'title' => 'Forum',
            'tag_page' => $tag
        ]);
    }

    /**
     * @Route("/forum/{id<\d+>}", name="forum_show")
     * @param Topic $topic
     * @return Response
     */
    public function show(Topic $topic): Response
    {
        return $this->render('forum/show.html.twig', [
            'topic' => $topic,
            'messages' => $topic->getMessages(),
            'menu' => 'forum'
        ]);
    }
}