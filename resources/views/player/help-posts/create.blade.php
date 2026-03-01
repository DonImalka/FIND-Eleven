<x-app-layout>
    <x-slot name="title">Create Help Post</x-slot>

    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">Create Help Post</h2>
            <a href="{{ route('player.help-posts.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to My Posts
            </a>
        </div>

        <div class="info-card" style="padding: 24px;">
            <form method="POST" action="{{ route('player.help-posts.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Title --}}
                <div style="margin-bottom: 20px;">
                    <label for="title" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">Post Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; outline: none;"
                           placeholder="e.g. Need support for cricket gear" required>
                    @error('title')
                        <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div style="margin-bottom: 20px;">
                    <label for="description" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">Description</label>
                    <textarea name="description" id="description" rows="6"
                              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; outline: none; resize: vertical;"
                              placeholder="Describe your situation and what kind of help you need..." required>{{ old('description') }}</textarea>
                    <p style="font-size: 0.75rem; color: #9CA3AF; margin-top: 4px;">Maximum 5000 characters</p>
                    @error('description')
                        <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Proof Document --}}
                <div style="margin-bottom: 24px;">
                    <label for="proof_document" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">
                        Proof Document <span style="color: #9CA3AF; font-weight: 400;">(optional)</span>
                    </label>
                    <input type="file" name="proof_document" id="proof_document"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; background: #F9FAFB;">
                    <p style="font-size: 0.75rem; color: #9CA3AF; margin-top: 4px;">
                        Accepted formats: PDF, JPG, PNG, DOC, DOCX. Max size: 5MB
                    </p>
                    @error('proof_document')
                        <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Box --}}
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 14px; margin-bottom: 24px;">
                    <p style="font-size: 0.8rem; color: #1E40AF;">
                        ℹ️ Your help post will be reviewed by your school before it becomes visible on the website. 
                        Upload any supporting documents (medical certificates, financial proof, etc.) to speed up approval.
                    </p>
                </div>

                {{-- Submit --}}
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('player.help-posts.index') }}" style="padding: 10px 20px; background: #E5E7EB; color: #374151; border-radius: 8px; font-size: 0.875rem; text-decoration: none;">
                        Cancel
                    </a>
                    <button type="submit" style="padding: 10px 20px; background: #4F46E5; color: white; border: none; border-radius: 8px; font-size: 0.875rem; cursor: pointer;">
                        Submit Help Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
