<?php

namespace Botble\Demo\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Demo\Http\Requests\DemoRequest;
use Botble\Demo\Repositories\Interfaces\DemoInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Demo\Tables\DemoTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Demo\Forms\DemoForm;
use Botble\Base\Forms\FormBuilder;

class DemoController extends BaseController
{
    /**
     * @var DemoInterface
     */
    protected $demoRepository;

    /**
     * @param DemoInterface $demoRepository
     */
    public function __construct(DemoInterface $demoRepository)
    {
        $this->demoRepository = $demoRepository;
    }

    /**
     * @param DemoTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(DemoTable $table)
    {
        page_title()->setTitle(trans('plugins/demo::demo.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/demo::demo.create'));

        return $formBuilder->create(DemoForm::class)->renderForm();
    }

    /**
     * @param DemoRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(DemoRequest $request, BaseHttpResponse $response)
    {
        $demo = $this->demoRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DEMO_MODULE_SCREEN_NAME, $request, $demo));

        return $response
            ->setPreviousUrl(route('demo.index'))
            ->setNextUrl(route('demo.edit', $demo->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $demo = $this->demoRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $demo));

        page_title()->setTitle(trans('plugins/demo::demo.edit') . ' "' . $demo->name . '"');

        return $formBuilder->create(DemoForm::class, ['model' => $demo])->renderForm();
    }

    /**
     * @param $id
     * @param DemoRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, DemoRequest $request, BaseHttpResponse $response)
    {
        $demo = $this->demoRepository->findOrFail($id);

        $demo->fill($request->input());

        $this->demoRepository->createOrUpdate($demo);

        event(new UpdatedContentEvent(DEMO_MODULE_SCREEN_NAME, $request, $demo));

        return $response
            ->setPreviousUrl(route('demo.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $demo = $this->demoRepository->findOrFail($id);

            $this->demoRepository->delete($demo);

            event(new DeletedContentEvent(DEMO_MODULE_SCREEN_NAME, $request, $demo));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $demo = $this->demoRepository->findOrFail($id);
            $this->demoRepository->delete($demo);
            event(new DeletedContentEvent(DEMO_MODULE_SCREEN_NAME, $request, $demo));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
