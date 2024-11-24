@props([
    'lastDeploymentInfo' => null,
    'lastDeploymentLink' => null,
    'resource' => null,
])
<nav class="flex pt-2 pb-10">
    <ol class="flex flex-wrap items-center gap-y-1">
        <li class="inline-flex items-center">
            <div class="flex items-center">
                <a class="text-xs truncate lg:text-sm"
                    href="{{ route('project.show', ['project_uuid' => data_get($resource, 'environment.project.uuid')]) }}">
                    {{ data_get($resource, 'environment.project.name', 'Undefined Name') }}</a>
                <x-dropdown>
                    <x-slot:trigger>
                        <svg aria-hidden="true" class="w-4 h-4 mx-1 font-bold cursor-pointer dark:text-warning hover:scale-110" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </x-slot:trigger>
                    <div class="flex flex-col gap-1">
                        @foreach($resource->environment->project->environments as $env)
                            <div class="px-4 py-2 text-xs text-coolgray-500">{{ $env->name }}</div>
                            @foreach($env->applications->sortBy('name') as $app)
                                <a href="{{ route('project.application.configuration', ['project_uuid' => $env->project->uuid, 'environment_name' => $env->name, 'application_uuid' => $app->uuid]) }}" 
                                   class="dropdown-item">
                                    {{ $app->name }}
                                </a>
                            @endforeach
                            @foreach($env->databases->sortBy('name') as $db)
                                <a href="{{ route('project.database.configuration', ['project_uuid' => $env->project->uuid, 'environment_name' => $env->name, 'database_uuid' => $db->uuid]) }}" 
                                   class="dropdown-item">
                                    {{ $db->name }}
                                </a>
                            @endforeach
                            @foreach($env->services->sortBy('name') as $service)
                                <a href="{{ route('project.service.configuration', ['project_uuid' => $env->project->uuid, 'environment_name' => $env->name, 'service_uuid' => $service->uuid]) }}" 
                                   class="dropdown-item">
                                    {{ $service->name }}
                                </a>
                            @endforeach
                        @endforeach
                    </div>
                </x-dropdown>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <a class="text-xs truncate lg:text-sm"
                    href="{{ route('project.resource.index', ['environment_name' => data_get($resource, 'environment.name'), 'project_uuid' => data_get($resource, 'environment.project.uuid')]) }}">
                    {{ data_get($resource, 'environment.name') }}</a>
                <x-dropdown>
                    <x-slot:trigger>
                        <svg aria-hidden="true" class="w-4 h-4 mx-1 font-bold cursor-pointer dark:text-warning hover:scale-110" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </x-slot:trigger>
                    <div class="flex flex-col gap-1">
                        @foreach($resource->environment->applications->sortBy('name') as $app)
                            <a href="{{ route('project.application.configuration', ['project_uuid' => $resource->environment->project->uuid, 'environment_name' => $resource->environment->name, 'application_uuid' => $app->uuid]) }}" 
                               class="dropdown-item">
                                {{ $app->name }}
                            </a>
                        @endforeach
                        @foreach($resource->environment->databases->sortBy('name') as $db)
                            <a href="{{ route('project.database.configuration', ['project_uuid' => $resource->environment->project->uuid, 'environment_name' => $resource->environment->name, 'database_uuid' => $db->uuid]) }}" 
                               class="dropdown-item">
                                {{ $db->name }}
                            </a>
                        @endforeach
                        @foreach($resource->environment->services->sortBy('name') as $service)
                            <a href="{{ route('project.service.configuration', ['project_uuid' => $resource->environment->project->uuid, 'environment_name' => $resource->environment->name, 'service_uuid' => $service->uuid]) }}" 
                               class="dropdown-item">
                                {{ $service->name }}
                            </a>
                        @endforeach
                    </div>
                </x-dropdown>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <span class="text-xs truncate lg:text-sm">{{ data_get($resource, 'name') }}</span>
                @if($resource->getMorphClass() !== 'App\Models\Service')
                    <x-dropdown>
                        <x-slot:trigger>
                            <svg aria-hidden="true" class="w-4 h-4 mx-1 font-bold cursor-pointer dark:text-warning hover:scale-110" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </x-slot:trigger>
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('project.application.configuration', $parameters) }}" class="dropdown-item">Configuration</a>
                            <a href="{{ route('project.application.deployment.index', $parameters) }}" class="dropdown-item">Deployments</a>
                            <a href="{{ route('project.application.logs', $parameters) }}" class="dropdown-item">Logs</a>
                            @if (!$resource->destination->server->isSwarm())
                                <a href="{{ route('project.application.command', $parameters) }}" class="dropdown-item">Terminal</a>
                            @endif
                        </div>
                    </x-dropdown>
                @endif
            </div>
        </li>
        @if ($resource->getMorphClass() == 'App\Models\Service')
            <x-status.services :service="$resource" />
        @else
            <x-status.index :resource="$resource" :title="$lastDeploymentInfo" :lastDeploymentLink="$lastDeploymentLink" />
        @endif
    </ol>
</nav>
